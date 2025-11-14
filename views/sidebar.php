<div class="sidebar">
    <a href="?page=list" class="menu-item">Danh sách</a>
    <a href="?page=borrows" class="menu-item">Phiếu mượn</a>
    <a href="?page=history" class="menu-item">Lịch sử</a>
    <a href="?page=favorites" class="menu-item">Yêu thích</a>

    <?php if (is_admin()): ?>
        <a href="?page=add" class="menu-item">Thêm sách</a>
        <a href="?page=topbooks" class="menu-item">Top sách</a>
        <a href="?page=penalties" class="menu-item">Phạt</a>
    <?php endif; ?>

</div>