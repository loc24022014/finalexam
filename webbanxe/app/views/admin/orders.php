<div class="header-section mb-4">
    <h2 class="section-title">Quản lý <span>Đặt Lịch</span></h2>
</div>

<div class="glass-card p-4">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Email</th>
                    <th>Ngày Hẹn</th>
                    <th>Cọc</th>
                    <th>PTTT</th>
                    <th>Trạng Thái</th>
                    <th style="text-align:right;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order->id ?></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($order->full_name ?: $order->username) ?></td>
                            <td><?= htmlspecialchars($order->email ?: 'N/A') ?></td>
                            <td style="color:var(--primary); font-weight:700;"><?= date('d/m/Y', strtotime($order->appointment_date)) ?></td>
                            <td style="font-weight:600;"><?= number_format($order->total_amount, 0, ',', '.') ?> ₫</td>
                            <td><span style="text-transform:uppercase; font-size:0.8rem;"><?= htmlspecialchars($order->payment_method) ?></span></td>
                            <td>
                                <?php if ($order->status == 'pending'): ?>
                                    <span class="badge" style="background:rgba(234, 179, 8, 0.2); color:#eab308; border-radius:0;">Chờ xác nhận</span>
                                <?php elseif ($order->status == 'confirmed'): ?>
                                    <span class="badge" style="background:rgba(16, 185, 129, 0.2); color:#10b981; border-radius:0;">Đã xác nhận</span>
                                <?php else: ?>
                                    <span class="badge" style="background:rgba(239, 68, 68, 0.2); color:#ef4444; border-radius:0;">Đã hủy</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right;">
                                <form action="<?= BASE_URL ?>/Admin/updateOrderStatus" method="POST" style="display:inline-flex; gap:0.5rem; align-items:center;">
                                    <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                    <select name="status" class="form-control" style="width:auto; padding:0.4rem; font-size:0.85rem; border-radius:0;">
                                        <option value="pending" <?= $order->status == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                        <option value="confirmed" <?= $order->status == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                        <option value="cancelled" <?= $order->status == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="padding:0.4rem 1rem; font-size:0.8rem;">Lưu</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted">Chưa có đơn đặt lịch nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
