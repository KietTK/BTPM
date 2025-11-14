<h2>Top sách được mượn</h2>
<table class="table">
    <tr>
        <th>Tiêu đề</th>
        <th>Lượt mượn</th>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= $r['total'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>