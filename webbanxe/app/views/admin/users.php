<div class="header-section mb-4">
    <h2 class="section-title">Danh sách <span>Khách Hàng</span></h2>
</div>

<div class="glass-card p-4">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Đăng Nhập</th>
                    <th>Họ Tên</th>
                    <th>Email</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Đăng Ký</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= $u->id ?></td>
                            <td style="font-weight:600; color:var(--primary);"><?= htmlspecialchars($u->username) ?></td>
                            <td><?= htmlspecialchars($u->full_name ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($u->email ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($u->phone ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($u->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">Chưa có khách hàng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
