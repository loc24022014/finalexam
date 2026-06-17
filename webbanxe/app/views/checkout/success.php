<?php
$pageTitle = 'Đặt lịch thành công - BanXe';
include 'app/views/shares/header.php';
?>

<div class="container py-5 text-center" style="max-width: 600px; margin: 0 auto;">
    <!-- Progress Steps -->
    <div class="checkout-steps mb-4" style="display: flex; justify-content: center; align-items: center; gap: 1.5rem; font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
        <span style="color: var(--text-dim); display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: rgba(0, 242, 254, 0.1); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; border: 1px solid var(--primary);">✓</span>
            Chọn xe đặt lịch
        </span>
        <span style="color: var(--text-dim);">➔</span>
        <span style="color: var(--text-dim); display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: rgba(243, 85, 255, 0.1); color: var(--secondary); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; border: 1px solid var(--secondary);">✓</span>
            Lịch hẹn & Đặt cọc
        </span>
        <span style="color: var(--text-dim);">➔</span>
        <span style="color: var(--primary); border-bottom: 2px solid var(--primary); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--primary); color: var(--bg-darker); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">3</span>
            Hoàn tất
        </span>
    </div>

    <div class="glass-card" style="padding: 3rem 2rem;">
        
        <div style="font-size: 5rem; line-height: 1; margin-bottom: 1rem;">
            <?= ($order->payment_status == 'completed' || $order->payment_method == 'cash') ? '🎉' : '⏳' ?>
        </div>
        
        <h1 style="margin-top:0; color: var(--primary);">
            <?= ($order->payment_method == 'cash') ? 'Đã ghi nhận yêu cầu!' : 'Thanh toán thành công!' ?>
        </h1>
        
        <p class="text-muted" style="font-size: 1.1rem; margin-bottom: 2rem;">
            Cảm ơn bạn đã tin tưởng BanXe. Mã lịch hẹn của bạn là <strong>#DH<?= str_pad($order->id, 4, '0', STR_PAD_LEFT) ?></strong>.
        </p>

        <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; text-align: left; margin-bottom: 2rem;">
            <div style="margin-bottom: 0.8rem; display: flex; justify-content: space-between;">
                <span class="text-muted">Trạng thái:</span>
                <strong style="color: var(--green);">Đã xác nhận lịch</strong>
            </div>
            <div style="margin-bottom: 0.8rem; display: flex; justify-content: space-between;">
                <span class="text-muted">Ngày xem xe:</span>
                <strong><?= date('d/m/Y', strtotime($order->appointment_date)) ?></strong>
            </div>
            <div style="margin-bottom: 0.8rem; display: flex; justify-content: space-between;">
                <span class="text-muted">Phương thức:</span>
                <strong>
                    <?php 
                        $methods = ['cash' => 'Tiền mặt', 'qr' => 'Chuyển khoản QR', 'momo' => 'MoMo', 'shopeepay' => 'ShopeePay', 'bank' => 'Ngân hàng'];
                        echo $methods[$order->payment_method] ?? 'Khác';
                    ?>
                </strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Đã thanh toán cọc:</span>
                <strong style="color: var(--primary); font-size: 1.2rem;">
                    <?= ($order->payment_status == 'completed') ? number_format($order->total_amount, 0, ',', '.') . ' ₫' : '0 ₫' ?>
                </strong>
            </div>
        </div>
        
        <?php if ($order->payment_method == 'cash' && $order->payment_status == 'pending'): ?>
            <div class="alert" style="background: rgba(255,204,0,0.1); border: 1px solid var(--primary); color: var(--primary); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-size:0.9rem;">
                ⚠️ Lưu ý: Vì bạn chọn thanh toán tại Showroom, xe chỉ được giữ chỗ ưu tiên, nếu có khách khác hoàn tất đặt cọc hoặc mua thẳng, xe có thể không còn. Bạn nhớ đến đúng ngày hẹn nhé!
            </div>
        <?php else: ?>
            <div class="alert" style="background: rgba(40,167,69,0.1); border: 1px solid #28a745; color: #28a745; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-size:0.9rem;">
                ✅ Xe đã được khóa và chắc chắn giữ lại cho bạn đến hết ngày hẹn.
            </div>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/Search" class="btn btn-primary btn-block btn-lg">Tiếp tục khám phá xe</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
