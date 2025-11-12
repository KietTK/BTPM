<h2>Phiếu mượn</h2>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Tựa sách</th>
        <th>Ngày mượn</th>
        <th>Ngày trả</th>
        <th></th>
    </tr>
    <?php foreach ($borrows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= $r['title'] ?></td>
            <td><?= $r['borrowed_at'] ?></td>
            <td><?= $r['returned_at'] ?? '-' ?></td>
            <td>
                <?php if (!$r['returned_at']): ?>
                    <a href="?page=return&id=<?= $r['id'] ?>" onclick="return confirm('Xác nhận trả?')">Trả sách</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>