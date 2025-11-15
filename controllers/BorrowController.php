<?php
class BorrowController
{
    public static function index($pdo)
    {
        auth_required();
        self::runPeriodicTasks($pdo);

        if (is_admin()) {
            $stmt = $pdo->query("
                SELECT b.id, b.book_id, b.user_id, b.borrowed_at, b.due_at, b.returned_at, b.status, b.renew_count, bo.title, u.name AS user_name
                FROM borrows b
                JOIN books bo ON b.book_id = bo.id
                JOIN users u ON b.user_id = u.id
                ORDER BY b.id DESC
            ");
            $borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            include 'views/borrows/list.php';
        } else {
            self::history($pdo);
        }
    }

    public static function borrow($pdo)
    {
        auth_required();
        $book_id = intval($_GET['id'] ?? 0);

        $stmt = $pdo->prepare("SELECT * FROM books WHERE id=?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$book) {
            $_SESSION['message'] = "Không tìm thấy sách.";
            header('Location:?page=list');
            exit;
        }

        if ($book['stock'] <= 0) {
            $_SESSION['message'] = "Sách hiện hết bản sao. Bạn có thể đặt chỗ.";
            header("Location:?page=reserve&id={$book_id}");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['id'];
            try {
                $pdo->beginTransaction();

                $pdo->prepare("UPDATE books SET stock = stock - 1 WHERE id=? AND stock>0")
                    ->execute([$book_id]);

                $borrowed_at = date('Y-m-d H:i:s');
                $due_at = date('Y-m-d H:i:s', strtotime('+7 days'));

                $pdo->prepare("INSERT INTO borrows (book_id, user_id, borrowed_at, due_at, status, renew_count)
                              VALUES (?, ?, ?, ?, 'borrowed', 0)")
                    ->execute([$book_id, $user_id, $borrowed_at, $due_at]);

                $pdo->commit();
                $_SESSION['message'] = "Mượn sách thành công!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['message'] = "Lỗi khi mượn sách.";
            }
            header('Location:?page=history');
            exit;
        }

        include 'views/borrows/form.php';
    }

    public static function returnBook($pdo)
    {
        auth_required();
        $borrow_id = intval($_GET['id'] ?? 0);

        $stmt = $pdo->prepare("SELECT * FROM borrows WHERE id=?");
        $stmt->execute([$borrow_id]);
        $b = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$b) {
            $_SESSION['message'] = "Phiếu mượn không tồn tại.";
            header('Location:?page=borrows');
            exit;
        }

        if ($b['returned_at']) {
            $_SESSION['message'] = "Phiếu này đã được trả trước đó.";
            header('Location:?page=borrows');
            exit;
        }

        try {
            $pdo->beginTransaction();

            $returned_at = date('Y-m-d H:i:s');
            $pdo->prepare("UPDATE borrows SET returned_at=?, status='returned' WHERE id=?")
                ->execute([$returned_at, $borrow_id]);

            $pdo->prepare("UPDATE books SET stock = stock + 1 WHERE id=?")
                ->execute([$b['book_id']]);

            if (!empty($b['due_at']) && strtotime($returned_at) > strtotime($b['due_at'])) {
                $daysLate = (int) ceil((strtotime($returned_at) - strtotime($b['due_at'])) / 86400);
                $amount = $daysLate * 5000;
                $pdo->prepare("INSERT INTO penalties (borrow_id, user_id, days_late, amount, paid) VALUES (?,?,?,?,0)")
                    ->execute([$borrow_id, $b['user_id'], $daysLate, $amount]);

                $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?,?)")
                    ->execute([$b['user_id'], "Bạn đã trả sách trễ $daysLate ngày. Phí: $amount VND."]);
            }

            $res = $pdo->prepare("SELECT id, user_id FROM reservations WHERE book_id=? AND status='waiting' ORDER BY created_at ASC LIMIT 1");
            $res->execute([$b['book_id']]);
            $next = $res->fetch(PDO::FETCH_ASSOC);

            if ($next) {
                $pdo->prepare("UPDATE reservations SET status='notified' WHERE id=?")->execute([$next['id']]);
                $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?,?)")
                    ->execute([$next['user_id'], "Sách bạn đặt chỗ đã có sẵn. Vui lòng mượn trong 24 giờ."]);
            }

            $pdo->commit();
            $_SESSION['message'] = "Trả sách thành công.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['message'] = "Lỗi khi trả sách.";
        }

        header('Location:?page=borrows');
        exit;
    }

    public static function reserve($pdo)
    {
        auth_required();
        $book_id = intval($_GET['id'] ?? 0);
        $user_id = $_SESSION['user']['id'];

        $stmt = $pdo->prepare("SELECT id FROM reservations WHERE user_id=? AND book_id=? AND status='waiting'");
        $stmt->execute([$user_id, $book_id]);
        if ($stmt->fetch()) {
            $_SESSION['message'] = "Bạn đã đặt chỗ sách này rồi.";
            header('Location:?page=list');
            exit;
        }

        $pdo->prepare("INSERT INTO reservations (user_id, book_id, created_at, status) VALUES (?,?,NOW(),'waiting')")
            ->execute([$user_id, $book_id]);

        $_SESSION['message'] = "Đặt chỗ thành công. Hệ thống sẽ thông báo khi có sách.";
        header('Location:?page=reservations');
        exit;
    }

    public static function reservationsList($pdo)
    {
        auth_required();

        $uid = $_SESSION['user']['id'];

        $stmt = $pdo->prepare("
        SELECT r.*, bo.title 
        FROM reservations r
        JOIN books bo ON r.book_id = bo.id
        WHERE r.user_id = ?
        ORDER BY r.created_at DESC
    ");
        $stmt->execute([$uid]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/borrows/reservations.php';
    }

    public static function history($pdo)
    {
        auth_required();
        $uid = $_SESSION['user']['id'];

        $stmt = $pdo->prepare("
            SELECT b.id, b.book_id, bo.title, b.borrowed_at, b.due_at, b.returned_at, b.status, b.renew_count
            FROM borrows b
            JOIN books bo ON b.book_id = bo.id
            WHERE b.user_id = ?
            ORDER BY b.id DESC
        ");
        $stmt->execute([$uid]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/borrows/history.php';
    }

    public static function penalties($pdo)
    {
        auth_required();
        if (is_admin()) {
            $stmt = $pdo->query("
                SELECT p.*, b.book_id, bo.title, u.name AS user_name
                FROM penalties p
                JOIN borrows b ON p.borrow_id = b.id
                JOIN books bo ON b.book_id = bo.id
                JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC
            ");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->prepare("
                SELECT p.*, b.book_id, bo.title
                FROM penalties p
                JOIN borrows b ON p.borrow_id = b.id
                JOIN books bo ON b.book_id = bo.id
                WHERE p.user_id = ?
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$_SESSION['user']['id']]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        include 'views/borrows/penalties.php';
    }

    public static function markPaid($pdo)
    {
        auth_required();
        if (!is_admin())
            die("403");

        $id = intval($_GET['id'] ?? 0);

        $pdo->prepare("UPDATE penalties SET paid=1 WHERE id=?")
            ->execute([$id]);

        $_SESSION['message'] = "Đã đánh dấu thanh toán.";
        header('Location:?page=penalties');
        exit;
    }

    public static function topUser($pdo)
    {
        auth_required();
        if (!is_admin())
            die("403");

        $stmt = $pdo->query("
        SELECT u.id, u.email, u.name, COUNT(b.id) AS total
        FROM users u
        JOIN borrows b ON u.id = b.user_id
        GROUP BY u.id, u.name
        ORDER BY total DESC
        LIMIT 10
    ");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/borrows/top_user.php';
    }

    public static function renew($pdo)
    {
        auth_required();
        $borrow_id = intval($_GET['id'] ?? 0);

        $stmt = $pdo->prepare("SELECT * FROM borrows WHERE id=?");
        $stmt->execute([$borrow_id]);
        $b = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$b) {
            $_SESSION['message'] = "Phiếu mượn không tồn tại.";
            header('Location:?page=history');
            exit;
        }

        if (!is_admin() && $b['user_id'] != ($_SESSION['user']['id'] ?? 0)) {
            $_SESSION['message'] = "Bạn không có quyền gia hạn phiếu này.";
            header('Location:?page=history');
            exit;
        }

        if (!empty($b['returned_at'])) {
            $_SESSION['message'] = "Phiếu đã trả, không thể gia hạn.";
            header('Location:?page=history');
            exit;
        }

        if ((int) $b['renew_count'] >= 2) {
            $_SESSION['message'] = "Bạn đã đạt giới hạn gia hạn (2 lần).";
            header('Location:?page=history');
            exit;
        }

        $r = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE book_id=? AND status='waiting'");
        $r->execute([$b['book_id']]);
        $waiting = (int) $r->fetchColumn();

        if ($waiting > 0) {
            $_SESSION['message'] = "Không thể gia hạn vì có người đang đặt chỗ cho sách này.";
            header('Location:?page=history');
            exit;
        }

        $newDue = date('Y-m-d H:i:s', strtotime($b['due_at'] . ' +7 days'));
        $pdo->prepare("UPDATE borrows SET due_at = ?, renew_count = renew_count + 1 WHERE id = ?")
            ->execute([$newDue, $borrow_id]);

        $_SESSION['message'] = "Gia hạn thành công. Hạn mới: $newDue";
        header('Location:?page=history');
        exit;
    }

    public static function runPeriodicTasks($pdo)
    {
        $pdo->prepare("UPDATE borrows SET status='late' WHERE status='borrowed' AND due_at < NOW()")
            ->execute();

        $pdo->prepare("UPDATE reservations SET status='expired' WHERE status='notified' AND created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)")
            ->execute();
    }
}
