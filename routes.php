<?php
require 'controllers/BookController.php';
require 'controllers/UserController.php';
require 'controllers/BorrowController.php';

$page = $_GET['page'] ?? 'list';

switch ($page) {
    //BookController
    case 'list':
        BookController::index($pdo);
        break;
    case 'add':
        BookController::add($pdo);
        break;
    case 'edit':
        BookController::edit($pdo);
        break;
    case 'delete':
        BookController::delete($pdo);
        break;
    //UserController
    case 'register':
        UserController::register($pdo);
        break;
    case 'login':
        UserController::login($pdo);
        break;
    case 'logout':
        UserController::logout();
        break;
    //BorrowController
    case 'borrow':
        BorrowController::borrow($pdo);
        break;
    case 'return':
        BorrowController::returnBook($pdo);
        break;
    case 'borrows':
        BorrowController::index($pdo);
        break;

    default:
        echo "<h3>Trang không tồn tại</h3>";
}
?>