<h2>Người dùng mượn sách nhiều nhất</h2>

<table class="table">
    <tr>
        <th>Tên</th>
        <th>Email</th>
        <th>Số lượt mượn</th>
    </tr>

    <?php foreach ($data as $r): ?>
        <tr>
            <td><?= $r['name'] ?></td>
            <td><?= $r['email'] ?></td>
            <td><?= $r['total'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>