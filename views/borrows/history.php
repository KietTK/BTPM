<h2>Lịch sử mượn của bạn</h2>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Sách</th>
        <th>Ngày mượn</th>
        <th>Ngày trả</th>
        <th>Trạng thái</th>
    </tr>
    <?php foreach ($borrows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= $r['borrowed_at'] ?></td>
            <td><?= $r['returned_at'] ?: '-' ?></td>
            <td>
                <?php
                $status = $r['status'];
                $status_class = 'status-' . $status;
                ?>
                <span class="status-badge <?= $status_class ?>">
                    <?= htmlspecialchars($status) ?>
                </span>
            </td>
        </tr>
    <?php endforeach; ?>
</table>