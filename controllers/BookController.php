<?php
class BookController
{
    public static function index($pdo)
    {
        $keyword = $_GET['keyword'] ?? '';

        if ($keyword !== '') {
            $stmt = $pdo->prepare("SELECT * FROM books 
            WHERE title LIKE ? OR author LIKE ? ORDER BY id DESC");
            $stmt->execute(["%$keyword%", "%$keyword%"]);
        } else {
            $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
        }

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/books/list.php';
    }

    public static function add($pdo)
    {
        if (!is_admin())
            die("403");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imgPath = self::handleUpload();

            $stmt = $pdo->prepare("INSERT INTO books (title,image,author,genre,pages,year,stock) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([
                $_POST['title'],
                $imgPath,
                $_POST['author'],
                $_POST['genre'],
                $_POST['pages'],
                $_POST['year'],
                $_POST['stock'],
            ]);

            $_SESSION['message'] = "Đã thêm sách.";
            header('Location:?page=list');
            exit;
        }
        $book = null;
        include 'views/books/form.php';
    }

    public static function edit($pdo)
    {
        if (!is_admin())
            die("403");
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id=?");
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imgPath = $book['image'];
            $newImg = self::handleUpload();
            if ($newImg)
                $imgPath = $newImg;

            $sql = "UPDATE books SET title=?, image=?, author=?, genre=?, pages=?, year=?, stock=? WHERE id=?";
            $pdo->prepare($sql)->execute([
                $_POST['title'],
                $imgPath,
                $_POST['author'],
                $_POST['genre'],
                $_POST['pages'],
                $_POST['year'],
                $_POST['stock'],
                $id
            ]);

            $_SESSION['message'] = "Cập nhật thành công.";
            header('Location:?page=list');
            exit;
        }

        include 'views/books/form.php';
    }

    public static function delete($pdo)
    {
        if (!is_admin())
            die("403");
        $id = (int) $_GET['id'];
        $pdo->prepare("DELETE FROM books WHERE id=?")->execute([$id]);
        $_SESSION['message'] = "Đã xóa sách.";
        header('Location:?page=list');
    }

    private static function handleUpload()
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK)
            return null;

        $file = $_FILES['image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed))
            return null;

        $newName = uniqid('book_', true) . '.' . $ext;
        $target = 'uploads/' . $newName;
        move_uploaded_file($file['tmp_name'], $target);
        return $target;
    }

    public static function topBooks($pdo)
    {
        $sql = "SELECT bo.title, COUNT(b.id) AS total
            FROM borrows b
            JOIN books bo ON b.book_id = bo.id
            GROUP BY b.book_id
            ORDER BY total DESC";

        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/books/top.php';
    }

    public static function rate($pdo)
    {
        if (!isset($_SESSION['user'])) {
            header('Location:?page=login');
            exit;
        }
        $user_id = $_SESSION['user']['id'];
        $book_id = (int) ($_POST['book_id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 0);
        $comment = $_POST['comment'] ?? '';

        if ($rating < 1 || $rating > 5) {
            $_SESSION['message'] = "Đánh giá phải 1-5.";
            header("Location:?page=list");
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM ratings WHERE user_id=? AND book_id=?");
        $stmt->execute([$user_id, $book_id]);
        if ($stmt->fetch()) {
            $pdo->prepare("UPDATE ratings SET rating=?, comment=?, created_at=NOW() WHERE user_id=? AND book_id=?")
                ->execute([$rating, $comment, $user_id, $book_id]);
        } else {
            $pdo->prepare("INSERT INTO ratings(user_id, book_id, rating, comment) VALUES(?,?,?,?)")
                ->execute([$user_id, $book_id, $rating, $comment]);
        }
        $_SESSION['message'] = "Cảm ơn đánh giá của bạn.";
        header("Location:?page=book&id=$book_id");
    }

    public static function detail($pdo)
    {
        $id = (int) ($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM books WHERE id=?");
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        $cmt = $pdo->prepare("
            SELECT r.rating, r.comment, r.created_at, u.name 
            FROM ratings r
            JOIN users u ON r.user_id = u.id
            WHERE r.book_id = ?
            ORDER BY r.created_at DESC
        ");
        $cmt->execute([$id]);
        $reviews = $cmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$book) {
            echo "<h3>Không tìm thấy sách</h3>";
            return;
        }
        $avg = $pdo->prepare("SELECT AVG(rating) avg, COUNT(*) cnt FROM ratings WHERE book_id=?");
        $avg->execute([$id]);
        $stat = $avg->fetch(PDO::FETCH_ASSOC);
        $rec = $pdo->prepare("SELECT * FROM books WHERE genre=? AND id<>? LIMIT 5");
        $rec->execute([$book['genre'], $id]);
        $recs = $rec->fetchAll(PDO::FETCH_ASSOC);
        include 'views/books/detail.php';
    }
}
?>