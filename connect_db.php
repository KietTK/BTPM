<?php
$host = 'localhost';
$db   = 'book_manager';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}
function is_admin(){
    return isset($_SESSION['user']) && $_SESSION['user']['is_admin'];
}
function auth_required() {
    if (!isset($_SESSION['user'])) {
        header('Location: ?page=login');
        exit;
    }
}
session_start();
?>