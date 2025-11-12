<?php
class BorrowController
{
    public static function index($pdo)
    {
        auth_required();
        $stmt = $pdo->query("SELECT b.id,b.book_id,b.borrowed_at,b.returned_at,bo.title 
                           FROM borrows b JOIN books bo ON b.book_id=bo.id ORDER BY b.id DESC");
        $borrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/borrows/list.php';
    }

    public static function borrow($pdo)
    {
        auth_required();
        $book_id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id=?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo->prepare("INSERT INTO borrows(book_id,user_id) VALUES(?,?)")
                ->execute([$book_id, $_SESSION['user']['id']]);
            $_SESSION['message'] = "Mượn sách thành công.";
            header('Location:?page=borrows');
            exit;
        }
        include 'views/borrows/form.php';
    }

    public static function returnBook($pdo)
    {
        auth_required();
        $id = (int) $_GET['id'];
        $pdo->prepare("UPDATE borrows SET returned_at=NOW() WHERE id=?")->execute([$id]);
        $_SESSION['message'] = "Đã trả sách.";
        header('Location:?page=borrows');
    }
}
?>