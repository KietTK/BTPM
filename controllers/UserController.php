<?php
class UserController
{
    public static function register($pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $name = $_POST['name'];
            $pass = $_POST['password'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
                $_SESSION['message'] = "Email hoặc mật khẩu không hợp lệ.";
                header('Location:?page=register');
                exit;
            }
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $_SESSION['message'] = "Email đã tồn tại.";
                header('Location:?page=register');
                exit;
            }
            $pdo->prepare("INSERT INTO users(email,password,name) VALUES(?,?,?)")
                ->execute([$email, password_hash($pass, PASSWORD_DEFAULT), $name]);
            $_SESSION['message'] = "Đăng ký thành công.";
            header('Location:?page=login');
            exit;
        }
        include 'views/users/register.php';
    }

    public static function login($pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($pass, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location:?page=list');
            } else
                $_SESSION['message'] = "Sai email hoặc mật khẩu.";
        }
        include 'views/users/login.php';
    }

    public static function logout()
    {
        session_destroy();
        header('Location:?page=login');
    }


    public static function addFavorite($pdo)
    {
        if (!isset($_SESSION['user'])) {
            header('Location:?page=login');
            exit;
        }
        $book_id = (int) ($_GET['id'] ?? 0);
        $user_id = $_SESSION['user']['id'];
        try {
            $pdo->prepare("INSERT IGNORE INTO favorites(user_id, book_id) VALUES(?,?)")
                ->execute([$user_id, $book_id]);
            $_SESSION['message'] = "Đã thêm vào yêu thích.";
        } catch (Exception $e) {
            $_SESSION['message'] = "Lỗi thêm yêu thích.";
        }
        header('Location:?page=list');
        exit;
    }

    public static function removeFavorite($pdo)
    {
        if (!isset($_SESSION['user'])) {
            header('Location:?page=login');
            exit;
        }
        $book_id = (int) ($_GET['id'] ?? 0);
        $user_id = $_SESSION['user']['id'];
        $pdo->prepare("DELETE FROM favorites WHERE user_id=? AND book_id=?")
            ->execute([$user_id, $book_id]);
        $_SESSION['message'] = "Đã xóa khỏi yêu thích.";
        header('Location:?page=favorites');
        exit;
    }

    public static function favoritesList($pdo)
    {
        if (!isset($_SESSION['user'])) {
            header('Location:?page=login');
            exit;
        }
        $user_id = $_SESSION['user']['id'];
        $stmt = $pdo->prepare("SELECT b.* FROM books b JOIN favorites f ON b.id=f.book_id WHERE f.user_id=?");
        $stmt->execute([$user_id]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/books/favorites.php';
    }
}
?>