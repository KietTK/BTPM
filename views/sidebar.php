<div class="sidebar">
    <a href="?page=list" class="menu-item">Danh sách</a>
    <a href="?page=borrows" class="menu-item">Phiếu mượn</a>
    <a href="?page=history" class="menu-item">Lịch sử</a>
    <a href="?page=favorites" class="menu-item">Yêu thích</a>
    <a href="?page=reservations" class="menu-item">Đặt trước</a>
    <a href="?page=penalties" class="menu-item">Phạt</a>

    <?php if (is_admin()): ?>
        <a href="?page=add" class="menu-item">Thêm sách</a>
        <a href="?page=topbooks" class="menu-item">Top sách</a>
        <a href="?page=top_user" class="menu-item">Top user</a>
    <?php endif; ?>

</div>