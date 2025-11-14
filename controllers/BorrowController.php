<?php
class BorrowController
{
    public static function index($pdo)
    {
        auth_required();
        self::runPeriodicTasks($pdo);
        $stmt = $pdo->query("SELECT b.id,b.book_id,b.borrowed_at,b.due_at,b.returned_at,b.status,bo.title
                             FROM borrows b JOIN books bo ON b.book_id=bo.id ORDER BY b.id DESC");
        $borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/borrows/list.php';
    }

    public static function borrow($pdo)
    {
        auth_required();
        $book_id = (int) ($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id=?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$book) {
            $_SESSION['message'] = "Không tìm thấy sách.";
            header('Location:?page=list');
            exit;
        }

        // check stock
        if ($book['stock'] <= 0) {
            $_SESSION['message'] = "Sách hiện hết bản sao. Bạn có thể đặt chỗ.";
            header('Location:?page=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo->beginTransaction();
            try {
                $pdo->prepare("UPDATE books SET stock = stock - 1 WHERE id=? AND stock>0")->execute([$book_id]);
                $due = date('Y-m-d H:i:s', strtotime('+7 days'));
                $pdo->prepare("INSERT INTO borrows (book_id,user_id,due_at,status) VALUES (?,?,?,?)")
                    ->execute([$book_id, $_SESSION['user']['id'], $due, 'borrowed']);
                $pdo->commit();
                $_SESSION['message'] = "Mượn thành công.";
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['message'] = "Lỗi mượn sách.";
            }
            header('Location:?page=borrows');
            exit;
        }
        include 'views/borrows/form.php';
    }

    public static function returnBook($pdo)
    {
        auth_required();
        $id = (int) ($_GET['id'] ?? 0);
        // fetch borrow
        $stmt = $pdo->prepare("SELECT * FROM borrows WHERE id=?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$r || $r['returned_at']) {
            $_SESSION['message'] = "Phiếu mượn không hợp lệ.";
            header('Location:?page=borrows');
            exit;
        }

        $pdo->beginTransaction();
        try {
            $pdo->prepare("UPDATE borrows SET returned_at=NOW(), status='returned' WHERE id=?")->execute([$id]);
            $pdo->prepare("UPDATE books SET stock = stock + 1 WHERE id=?")->execute([$r['book_id']]);
            if (strtotime(date('Y-m-d H:i:s')) > strtotime($r['due_at'])) {
                $daysLate = ceil((time() - strtotime($r['due_at'])) / 86400);
                $amount = $daysLate * 5000;
                $pdo->prepare("INSERT INTO penalties (borrow_id,user_id,days_late,amount) VALUES (?,?,?,?)")
                    ->execute([$id, $r['user_id'], $daysLate, $amount]);
                $pdo->prepare("INSERT INTO notifications(user_id,message) VALUES (?,?)")
                    ->execute([$r['user_id'], "Bạn trả sách trễ $daysLate ngày. Phí: $amount VND."]);
            }
            $res = $pdo->prepare("SELECT id,user_id FROM reservations WHERE book_id=? AND status='waiting' ORDER BY created_at ASC LIMIT 1");
            $res->execute([$r['book_id']]);
            $next = $res->fetch(PDO::FETCH_ASSOC);
            if ($next) {
                $pdo->prepare("UPDATE reservations SET status='notified' WHERE id=?")->execute([$next['id']]);
                $pdo->prepare("INSERT INTO notifications(user_id,message) VALUES(?,?)")
                    ->execute([$next['user_id'], "Sách bạn đặt chỗ đã có sẵn. Vui lòng mượn trong 24h."]);
            }
            $pdo->commit();
            $_SESSION['message'] = "Trả sách thành công.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['message'] = "Lỗi khi trả sách.";
        }
        header('Location:?page=borrows');
    }

    public static function reserve($pdo)
    {
        auth_required();
        $book_id = (int) ($_GET['id'] ?? 0);
        $user_id = $_SESSION['user']['id'];
        $pdo->prepare("INSERT IGNORE INTO reservations (user_id,book_id) VALUES (?,?)")->execute([$user_id, $book_id]);
        $_SESSION['message'] = "Bạn đã đặt chỗ. Khi có sách hệ thống sẽ thông báo.";
        header('Location:?page=list');
    }

    public static function history($pdo)
    {
        auth_required();
        $uid = $_SESSION['user']['id'];
        $stmt = $pdo->prepare("SELECT b.id, bo.title, b.borrowed_at, b.returned_at, b.status FROM borrows b JOIN books bo ON b.book_id=bo.id WHERE b.user_id=? ORDER BY b.id DESC");
        $stmt->execute([$uid]);
        $borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/borrows/history.php';
    }

    public static function penalties($pdo)
    {
        auth_required();
        if (is_admin()) {
            $stmt = $pdo->query("SELECT p.*, u.name, bo.title FROM penalties p JOIN users u ON p.user_id=u.id JOIN borrows b ON p.borrow_id=b.id JOIN books bo ON b.book_id=bo.id ORDER BY p.created_at DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->prepare("SELECT p.*, bo.title FROM penalties p JOIN borrows b ON p.borrow_id=b.id JOIN books bo ON b.book_id=bo.id WHERE p.user_id=? ORDER BY p.created_at DESC");
            $stmt->execute([$_SESSION['user']['id']]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        include 'views/borrows/penalties.php';
    }

    public static function runPeriodicTasks($pdo)
    {
        $pdo->prepare("UPDATE borrows SET status='late' WHERE status='borrowed' AND due_at < NOW()")->execute();
        $pdo->prepare("UPDATE reservations SET status='expired' WHERE status='notified' AND created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)")->execute();
    }
}
?>