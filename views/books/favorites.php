<h2>Sách yêu thích của bạn</h2>
<?php if (empty($books)): ?>
    <p>Chưa có sách yêu thích.</p> <?php else: ?>
    <div class="book-grid">
        <?php foreach ($books as $b): ?>
            <div class="book-card">
                <img src="<?= $b['image'] ?: 'uploads/default.jpg' ?>" class="book-thumb">
                <div>
                    <div class="book-title"><?= htmlspecialchars($b['title']) ?></div>
                    <div><a class="btn" href="?page=book&id=<?= $b['id'] ?>">Xem</a>
                        <a class="btn" href="?page=fav_remove&id=<?= $b['id'] ?>">Bỏ yêu thích</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>