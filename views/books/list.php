<h2>Danh sách sách</h2>
<table class="table">
    <tr>
        <th>Tiêu đề</th>
        <th>Tác giả</th>
        <th>Thể loại</th>
        <th>Trang</th>
        <th>Năm</th>
        <th></th>
    </tr>
    <?php foreach ($books as $b): ?>
        <tr>
            <td>
                <?php if (!empty($b['image'])): ?>
                    <img src="<?= $b['image'] ?>" alt="Cover" style="width:60px;height:80px;object-fit:cover;">
                <?php else: ?>
                    <span style="color:#999;">Không có ảnh</span>
                <?php endif; ?>
            </td>
            <td><?= $b['title'] ?></td>
            <td><?= $b['author'] ?></td>
            <td><?= $b['genre'] ?></td>
            <td><?= $b['pages'] ?></td>
            <td><?= $b['year'] ?></td>
            <td>
                <?php if (is_admin()): ?>
                    <a href="?page=edit&id=<?= $b['id'] ?>">Sửa</a> |
                    <a href="?page=delete&id=<?= $b['id'] ?>" onclick="return confirm('Xóa?')">Xóa</a> |
                <?php endif; ?>
                <a href="?page=borrow&id=<?= $b['id'] ?>">Mượn</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>