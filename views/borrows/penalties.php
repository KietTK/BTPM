    <h2 style="margin-bottom: 20px;">Danh sách phí phạt</h2>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <?php if (is_admin()): ?>
                        <th>User</th>
                    <?php endif; ?>

                    <th>Sách</th>
                    <th>Số ngày trễ</th>
                    <th>Số tiền</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>

                    <?php if (is_admin()): ?>
                        <th>Hành động</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="10" style="text-align:center;">Không có khoản phạt nào.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($rows as $p): ?>
                <tr>
                    
                    <?php if (is_admin()): ?>
                        <td>
                            <?= htmlspecialchars($p['user_name'] ?? "User #".$p['user_id']) ?>
                        </td>
                    <?php endif; ?>

                    <td><?= htmlspecialchars($p['title']) ?></td>

                    <td>
                        <?= $p['days_late'] ?> ngày
                    </td>

                    <td>
                        <?= number_format($p['amount']) ?> đ
                    </td>

                    <td><?= $p['created_at'] ?></td>

                    <td>
                        <?php if ($p['paid'] == 1): ?>
                            <span class="badge badge-paid">Đã thanh toán</span>
                        <?php else: ?>
                            <span class="badge badge-unpaid">Chưa thanh toán</span>
                        <?php endif; ?>
                    </td>

                    <?php if (is_admin()): ?>
                        <td>
                            <?php if ($p['paid'] == 0): ?>
                                <a href="?page=mark_paid&id=<?= $p['id'] ?>"
                                   class="btn btn-primary" 
                                   style="padding: 5px 12px; font-size:14px;">
                                    Đánh dấu đã thanh toán
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Đã xử lý</button>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>