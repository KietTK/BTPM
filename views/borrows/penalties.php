<h2>Phí phạt</h2>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Sách</th>
        <th>Người</th>
        <th>Ngày trễ</th>
        <th>Số tiền</th>
        <th>Đã trả</th>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['name'] ?? ($_SESSION['user']['name'] ?? '')) ?></td>
            <td><?= intval($r['days_late']) ?></td>
            <td><?= number_format($r['amount']) ?> VND</td>
            <td><?= $r['paid'] ? 'Có' : 'Chưa' ?></td>
        </tr>
    <?php endforeach; ?>
</table>