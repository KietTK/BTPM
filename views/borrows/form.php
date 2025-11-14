<div class="borrow-confirm-container">

    <h2>Mượn sách</h2>

    <p>
        Bạn sắp mượn quyển:
        <strong><?= htmlspecialchars($book['title']) ?></strong>
    </p>
    <p>Hạn mượn mặc định: <strong>7 ngày</strong></p>

    <form method="post" style="margin-bottom: 12px;">
        <button type="submit" class="btn">Xác nhận mượn</button>
    </form>

    <a href="?page=list" class="btn btn-secondary">Quay lại</a>

</div>