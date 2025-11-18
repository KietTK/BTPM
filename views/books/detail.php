<div class="book-detail-card">
    <h2><?= htmlspecialchars($book['title']) ?></h2>

    <div class="book-detail-container">
        <img src="<?= $book['image'] ?: 'uploads/default.jpg' ?>" class="book-detail-image">

        <div class="book-detail-info">
            <p><b>Tác giả:</b> <?= htmlspecialchars($book['author']) ?></p>
            <p><b>Thể loại:</b> <?= htmlspecialchars($book['genre']) ?></p>
            <p><b>Số trang:</b> <?= intval($book['pages']) ?></p>
            <p><b>Năm:</b> <?= intval($book['year']) ?></p>
            <p><b>Stock:</b> <?= intval($book['stock']) ?></p>
            <p><b>Đánh giá trung bình:</b>
                <?= $stat['cnt'] ? round($stat['avg'], 2) . " ({$stat['cnt']} lượt)" : "Chưa có" ?>
            </p>

            <div class="book-detail-actions">
                <?php if (isset($_SESSION['user'])): ?>
                    <a class="btn" href="?page=borrow&id=<?= $book['id'] ?>">Mượn</a>
                    <a class="btn" href="?page=fav_add&id=<?= $book['id'] ?>">Yêu thích</a>
                    <a class="btn" href="?page=reserve&id=<?= $b['id'] ?>">Đặt chỗ</a>
                <?php else: ?>
                    <a class="btn" href="?page=login">Đăng nhập để mượn</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <div class="review-form">
        <h3>Gửi đánh giá</h3>
        <?php if (isset($_SESSION['user'])): ?>
            <form method="post" action="?page=rate">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">

                <div class="form-group">
                    <label for="rating">Điểm:</label>
                    <select name="rating" id="rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="comment">Bình luận (tùy chọn):</label>
                    <textarea name="comment" id="comment" placeholder="Cảm nghĩ của bạn..."></textarea>
                </div>

                <button type="submit">Gửi</button>
            </form>
        <?php else: ?>
            <p>Đăng nhập để đánh giá.</p>
        <?php endif; ?>
    </div>

    <hr class="section-divider">

    <div class="user-reviews-section">
        <h3>Đánh giá từ người dùng khác</h3>

        <?php if (empty($reviews)): ?>
            <p>Chưa có đánh giá nào.</p>
        <?php else: ?>
            <?php foreach ($reviews as $rv): ?>
                <div class="review-item">
                    <p><b><?= htmlspecialchars($rv['name']) ?></b> –
                        <?= $rv['rating'] ?>/5
                    </p>

                    <?php if (!empty($rv['comment'])): ?>
                        <p><?= nl2br(htmlspecialchars($rv['comment'])) ?></p>
                    <?php endif; ?>

                    <span class="review-date">
                        <?= date("d/m/Y H:i", strtotime($rv['created_at'])) ?>
                    </span>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <hr class="section-divider">

    <div class="suggestions-section">
        <h3>Gợi ý cùng thể loại</h3>
        <div class="suggestions-list">
            <?php foreach ($recs as $r): ?>
                <div><a href="?page=book&id=<?= $r['id'] ?>"><?= htmlspecialchars($r['title']) ?></a></div>
            <?php endforeach; ?>
        </div>
    </div>

</div>