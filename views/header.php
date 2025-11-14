<header class="main-header">
    <div class="header-left">
        <h1>BookManager</h1>
    </div>

    <div class="header-right">
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="?page=login" class="header-btn">Đăng nhập</a>
            <a href="?page=register" class="header-btn">Đăng ký</a>
        <?php else: ?>
            <span class="welcome">Xin chào, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
            <a href="?page=logout" class="header-btn logout-btn">Đăng xuất</a>
        <?php endif; ?>
    </div>
</header>