<div class="nav">
    <a href="?page=list">Sách</a>
    <?php if (isset($_SESSION['user'])): ?>
        <span>Xin chào, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a href="?page=borrows">Phiếu mượn</a>
        <?php if (is_admin()): ?><a href="?page=add">Thêm sách</a><?php endif; ?>
        <a href="?page=logout">Đăng xuất</a>
    <?php else: ?>
        <a href="?page=login">Đăng nhập</a>
        <a href="?page=register">Đăng ký</a>
    <?php endif; ?>
</div>