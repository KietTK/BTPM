<?php
function borrow($pdo)
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
?>