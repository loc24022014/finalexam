<div class="header-section mb-4">
    <h2 class="section-title">Tổng quan <span>Hệ thống</span></h2>
</div>

<div class="dashboard-grid">
    <div class="stat-card glass-card">
        <div class="stat-icon">🏎️</div>
        <div class="stat-info">
            <h4>Sản Phẩm</h4>
            <div class="val"><?= $totalProducts ?></div>
        </div>
    </div>
    <div class="stat-card glass-card">
        <div class="stat-icon" style="background:#0ea5e9;">📅</div>
        <div class="stat-info">
            <h4>Đơn Đặt Lịch</h4>
            <div class="val"><?= $totalOrders ?></div>
        </div>
    </div>
    <div class="stat-card glass-card">
        <div class="stat-icon" style="background:#10b981;">👥</div>
        <div class="stat-info">
            <h4>Khách Hàng</h4>
            <div class="val"><?= $totalUsers ?></div>
        </div>
    </div>
</div>

<div class="glass-card p-4" style="padding: 2rem;">
    <h3 style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:800; font-style:italic; text-transform:uppercase; margin-top:0; border-bottom:1px solid var(--border-strong); padding-bottom:1rem; margin-bottom:1.5rem;">Đơn đặt lịch gần đây</h3>
    
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Ngày Hẹn</th>
                    <th>Số Tiền Cọc</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentOrders)): ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order->id ?></td>
                            <td><?= htmlspecialchars($order->full_name ?: $order->username) ?></td>
                            <td style="font-weight:600; color:var(--primary);"><?= date('d/m/Y', strtotime($order->appointment_date)) ?></td>
                            <td style="font-weight:600;"><?= number_format($order->total_amount, 0, ',', '.') ?> ₫</td>
                            <td>
                                <?php if ($order->status == 'pending'): ?>
                                    <span class="badge" style="background:rgba(234, 179, 8, 0.2); color:#eab308; border-radius:0;">Chờ xác nhận</span>
                                <?php elseif ($order->status == 'confirmed'): ?>
                                    <span class="badge" style="background:rgba(16, 185, 129, 0.2); color:#10b981; border-radius:0;">Đã xác nhận</span>
                                <?php else: ?>
                                    <span class="badge" style="background:rgba(239, 68, 68, 0.2); color:#ef4444; border-radius:0;">Đã hủy</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Chưa có đơn nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
