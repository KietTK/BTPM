<?php
require 'controllers/BookController.php';
require 'controllers/UserController.php';
require 'controllers/BorrowController.php';

$page = $_GET['page'] ?? 'list';

switch ($page) {

    // BOOKS
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
    case 'book':
        BookController::detail($pdo);
        break;
    case 'rate':
        BookController::rate($pdo);
        break;
    case 'topbooks':
        BookController::topBooks($pdo);
        break;

    // USERS
    case 'register':
        UserController::register($pdo);
        break;
    case 'login':
        UserController::login($pdo);
        break;
    case 'logout':
        UserController::logout();
        break;
    case 'favorites':
        UserController::favoritesList($pdo);
        break;
    case 'fav_add':
        UserController::addFavorite($pdo);
        break;
    case 'fav_remove':
        UserController::removeFavorite($pdo);
        break;

    // BORROWS
    case 'borrow':
        BorrowController::borrow($pdo);
        break;
    case 'borrows':
        BorrowController::index($pdo);
        break;
    case 'history':
        BorrowController::history($pdo);
        break;
    case 'return':
        BorrowController::returnBook($pdo);
        break;
    case 'reserve':
        BorrowController::reserve($pdo);
        break;
    case 'penalties':
        BorrowController::penalties($pdo);
        break;
    case 'renew':
        BorrowController::renew($pdo);
        break;
    case 'reservations':
        BorrowController::reservationsList($pdo);
        break;
    case 'top_user':
        BorrowController::topUser($pdo);
        break;

    default:
        echo "<h3>Trang không tồn tại</h3>";
}
?>