<?php
class BookController
{
    public static function index($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/books/list.php';
    }

    public static function add($pdo)
    {
        if (!is_admin())die("403");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $pdo->prepare("INSERT INTO books(title,author,genre,pages,year) VALUES(?,?,?,?,?)");
            $stmt->execute([$_POST['title'], $_POST['author'], $_POST['genre'], $_POST['pages'], $_POST['year']]);
            $_SESSION['message'] = "Đã thêm sách.";
            header('Location:?page=list');
            exit;
        }
        $book = null;
        include 'views/books/form.php';
    }

    public static function edit($pdo)
    {
        if (!is_admin())die("403");
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id=?");
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sql = "UPDATE books SET title=?,author=?,genre=?,pages=?,year=? WHERE id=?";
            $pdo->prepare($sql)->execute([$_POST['title'], $_POST['author'], $_POST['genre'], $_POST['pages'], $_POST['year'], $id]);
            $_SESSION['message'] = "Cập nhật thành công.";
            header('Location:?page=list');
            exit;
        }
        include 'views/books/form.php';
    }
    
    public static function delete($pdo)
    {
        if (!is_admin())die("403");
        $id = (int) $_GET['id'];
        $pdo->prepare("DELETE FROM books WHERE id=?")->execute([$id]);
        $_SESSION['message'] = "Đã xóa sách.";
        header('Location:?page=list');
    }
}
?>