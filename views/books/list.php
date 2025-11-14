<h2>Danh sách sách</h2>

<form method="get" class="search-form">
    <input type="hidden" name="page" value="list">
    <input type="text" name="keyword" placeholder="Tìm theo tên/tác giả..."
        value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
    <button class="btn" type="submit">Tìm</button>
</form>

<div class="book-grid">
    <?php foreach ($books as $b): ?>
        <div class="book-card">
            <img src="<?= $b['image'] ?: 'uploads/default.jpg' ?>" class="book-thumb" alt="cover">

            <div class="book-info">
                <div class="book-title"><?= htmlspecialchars($b['title']) ?></div>
                <div class="book-meta">Tác giả: <b><?= htmlspecialchars($b['author']) ?></b></div>
                <div class="book-meta">Thể loại: <?= htmlspecialchars($b['genre']) ?></div>
                <div class="book-meta">Stock: <?= intval($b['stock']) ?></div>

                <div class="book-actions">
                    <a class="btn" href="?page=book&id=<?= $b['id'] ?>">Xem</a>
                    <?php if (is_admin()): ?>
                        <a class="btn" href="?page=edit&id=<?= $b['id'] ?>">Sửa</a>
                        <a class="btn btn-danger" href="?page=delete&id=<?= $b['id'] ?>"
                            onclick="return confirm('Xóa?')">Xóa</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user'])): ?>
                        <a class="btn" href="?page=borrow&id=<?= $b['id'] ?>">Mượn</a>
                        <a class="btn" href="?page=fav_add&id=<?= $b['id'] ?>">Yêu thích</a>
                        <a class="btn" href="?page=reserve&id=<?= $b['id'] ?>">Đặt trước</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>