<?php
require 'connect_db.php';
$page = $_GET['page'] ?? 'list';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Quản lý sách</title>
    <link rel="stylesheet" href="css/main.css">
    <?php
    if (in_array($page, ['list', 'add', 'edit', 'topbooks', 'topusers', 'favorites', 'book'])) {
        echo '<link rel="stylesheet" href="css/books.css">';
    }
    if (in_array($page, ['login', 'register'])) {
        echo '<link rel="stylesheet" href="css/users.css">';
    }
    if (in_array($page, ['borrow', 'borrows', 'history', 'penalties', 'reservation', 'top_user'])) {
        echo '<link rel="stylesheet" href="css/borrows.css">';
    }
    ?>
</head>

<body>
    <?php include 'views/header.php'; ?>

    <div class="layout">
        <?php include 'views/sidebar.php'; ?>

        <div class="container">
            <?php
            if (isset($_SESSION['message'])) {
                $msg = $_SESSION['message'];
                unset($_SESSION['message']);
                include 'views/message.php';
            }
            require 'routes.php';
            ?>
        </div>
    </div>

    <?php include 'views/footer.php'; ?>
</body>

</html>