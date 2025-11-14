<h2>Danh sách phiếu mượn</h2>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Sách</th>
        <th>Người mượn</th>
        <th>Ngày mượn</th>
        <th>Hạn trả</th>
        <th>Ngày trả</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($borrows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?php
            $stmt = $pdo->prepare("SELECT name FROM users WHERE id=(SELECT user_id FROM borrows WHERE id=?)");
            $stmt->execute([$r['id']]);
            $u = $stmt->fetchColumn();
            echo htmlspecialchars($u);
            ?></td>
            <td><?= $r['borrowed_at'] ?></td>
            <td><?= $r['due_at'] ?? '-' ?></td>
            <td><?= $r['returned_at'] ?? '-' ?></td>
            <td>
                <?php
                $status = $r['status'];
                $status_class = 'status-' . $status;
                if ($status == 'pending' && strtotime($r['due_at']) < time()) {
                    $status_class = 'status-overdue';
                    $status = 'overdue';
                }
                ?>
                <span class="status-badge <?= $status_class ?>">
                    <?= htmlspecialchars($status) ?>
                </span>
            </td>
            <td>
                <?php if (!$r['returned_at'] && (is_admin() || ($_SESSION['user']['id'] ?? 0) == ($r['user_id'] ?? 0))): ?>
                    <a class="btn" href="?page=return&id=<?= $r['id'] ?>" onclick="return confirm('Xác nhận trả?')">Trả sách</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>