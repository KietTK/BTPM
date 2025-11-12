<?php
require 'connect_db.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Quản lý sách</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'views/header.php'; ?>

    <div class="container">
        <?php
        if (isset($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            unset($_SESSION['message']);
            include 'views/message.php';
        }
        include 'routes.php';
        ?>
    </div>

    <?php include 'views/footer.php'; ?>
</body>

</html>