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
}
?>