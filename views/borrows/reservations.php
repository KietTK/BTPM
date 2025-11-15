<h2>Danh sách đặt chỗ</h2>

<table class="table">
    <tr>
        <th>ID</th>
        <th>Sách</th>
        <th>Ngày đặt</th>
        <th>Trạng thái</th>
    </tr>

    <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= $r['created_at'] ?></td>
            <td>
                <?php if ($r['status'] === "waiting"): ?>
                    <span class="badge badge-warning">Đang chờ</span>
                <?php elseif ($r['status'] === "notified"): ?>
                    <span class="badge badge-info">Đã báo có sách</span>
                <?php elseif ($r['status'] === "used"): ?>
                    <span class="badge badge-success">Đã mượn</span>
                <?php else: ?>
                    <span class="badge badge-danger">Hết hạn</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>