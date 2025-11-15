<h2 class="page-title">Lịch sử mượn sách</h2>

<table class="table">
    <tr>
        <th>ID</th>
        <th>Sách</th>
        <th>Ngày mượn</th>
        <th>Hạn trả</th>
        <th>Ngày trả</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($records as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= $r['borrowed_at'] ?></td>
            <td><?= $r['due_at'] ?></td>
            <td><?= $r['returned_at'] ?: "-" ?></td>

            <td>
                <?php if ($r['returned_at']): ?>
                    <span class="badge badge-success">Đã trả</span>
                <?php elseif (strtotime($r['due_at']) < time()): ?>
                    <span class="badge badge-danger">Quá hạn</span>
                <?php else: ?>
                    <span class="badge badge-info">Đang mượn</span>
                <?php endif; ?>
            </td>

            <td>
                <?php if (!$r['returned_at']): ?>

                    <a href="?page=return&id=<?= $r['id'] ?>" class="btn btn-danger">
                        Trả
                    </a>

                    <a href="?page=renew&id=<?= $r['id'] ?>" class="btn btn-secondary">
                        Gia hạn
                    </a>

                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>