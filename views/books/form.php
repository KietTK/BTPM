<h2><?= $book ? 'Sửa sách' : 'Thêm sách mới' ?></h2>
<form method="post" enctype="multipart/form-data">
    Tiêu đề:<input name="title" value="<?= $book['title'] ?? '' ?>" required>
    Ảnh bìa: <input type="file" name="image" accept="image/*"><br>
    <?php if (!empty($book['image'])): ?>
        <img src="<?= $book['image'] ?>" alt="Cover" style="max-width:120px;margin:5px 0;">
    <?php endif; ?><br>
    Tác giả:<input name="author" value="<?= $book['author'] ?? '' ?>" required>
    Thể loại:<input name="genre" value="<?= $book['genre'] ?? '' ?>">
    Số trang:<input type="number" name="pages" value="<?= $book['pages'] ?? 0 ?>">
    Năm:<input type="number" name="year" value="<?= $book['year'] ?? date('Y') ?>">
    <button type="submit">Lưu</button>
</form>
<a href="?page=list">Quay lại</a>