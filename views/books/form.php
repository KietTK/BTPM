<form method="post" enctype="multipart/form-data" class="form-container">

    <h2><?= $book ? 'Sửa sách' : 'Thêm sách mới' ?></h2>

    <div class="form-body-grid">

        <div class="form-image-column">
            <div class="form-group">
                <label for="image">
                    <?php if (!empty($book['image'])): ?>
                        <img src="<?= $book['image'] ?>" class="image-preview" alt="Ảnh bìa">
                    <?php else: ?>
                        <span class="image-placeholder">
                            <span>Nhấn để tải ảnh lên</span>
                        </span>
                    <?php endif; ?>
                </label>
                <input id="image" type="file" name="image" accept="image/*">
            </div>
        </div>

        <div class="form-fields-column">
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input id="title" name="title" value="<?= htmlspecialchars($book['title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="author">Tác giả:</label>
                <input id="author" name="author" value="<?= htmlspecialchars($book['author'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="genre">Thể loại:</label>
                <input id="genre" name="genre" value="<?= htmlspecialchars($book['genre'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="pages">Số trang:</label>
                <input id="pages" type="number" name="pages" value="<?= intval($book['pages'] ?? 0) ?>">
            </div>

            <div class="form-group">
                <label for="year">Năm xuất bản:</label>
                <input id="year" type="number" name="year" value="<?= intval($book['year'] ?? date('Y')) ?>">
            </div>

            <div class="form-group">
                <label for="stock">Số bản (stock):</label>
                <input id="stock" type="number" name="stock" value="<?= intval($book['stock'] ?? 1) ?>" min="0">
            </div>
        </div>

    </div> <button type="submit">Lưu</button>

</form>

<a href="?page=list" class="btn btn-secondary" style="margin-top: 16px;">< Quay lại</a>