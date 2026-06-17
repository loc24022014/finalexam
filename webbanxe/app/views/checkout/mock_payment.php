<?php
$pageTitle = 'Thanh toán Đặt cọc - BanXe';
include 'app/views/shares/header.php';

// Thong tin ngan hang chinh
$bankName    = 'loc fuho';
$bankCode    = 'MB';          // Ma ngan hang MB (MBBank)
$bankAccNum  = '19918112005'; // STK cua loc fuho
$bankOwner   = 'LOC FUHO';
$amount      = $order->total_amount;
$content     = 'Coc xe DH' . $order->id;

// VietQR URL chinh thuc: https://vietqr.io/
$qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$bankAccNum}-compact2.png?amount={$amount}&addInfo=" . urlencode($content) . "&accountName=" . urlencode($bankOwner);
?>

<div class="container py-5" style="max-width: 600px; margin: 0 auto;">
    <div class="glass-card text-center" style="padding: 2.5rem;">
        
        <?php if ($method == 'qr'): ?>
            <h2 style="margin-top:0;">📱 Quét mã QR để thanh toán</h2>
            <p class="text-muted">Dùng app ngân hàng bất kỳ hỗ trợ VietQR để quét.</p>
            
            <!-- QR Code VietQR thực -->
            <div style="background: white; padding: 1rem; border-radius: 12px; display: inline-block; margin: 1.5rem 0; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <img src="<?= $qrUrl ?>" alt="VietQR" style="width: 260px; height: 260px; border-radius: 8px; display: block;">
            </div>
            
            <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 8px; text-align: left; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.1);">
                <div style="margin-bottom: 0.6rem; display:flex; justify-content:space-between;">
                    <span class="text-muted">Ngân hàng:</span>
                    <strong>MB Bank (MBBank)</strong>
                </div>
                <div style="margin-bottom: 0.6rem; display:flex; justify-content:space-between;">
                    <span class="text-muted">Chủ tài khoản:</span>
                    <strong><?= htmlspecialchars($bankOwner) ?></strong>
                </div>
                <div style="margin-bottom: 0.6rem; display:flex; justify-content:space-between;">
                    <span class="text-muted">Số tài khoản:</span>
                    <strong style="color:var(--primary); font-size:1.05rem; letter-spacing:0.05em;"><?= $bankAccNum ?></strong>
                </div>
                <div style="margin-bottom: 0.6rem; display:flex; justify-content:space-between;">
                    <span class="text-muted">Số tiền cọc:</span>
                    <strong style="color:var(--red); font-size:1.15rem;"><?= number_format($amount, 0, ',', '.') ?> ₫</strong>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span class="text-muted">Nội dung:</span>
                    <strong><?= htmlspecialchars($content) ?></strong>
                </div>
            </div>
            
        <?php elseif ($method == 'momo'): ?>
            <div style="background: #a50064; color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
                <h1 style="margin:0; font-size:2.5rem;">MoMo</h1>
                <p style="opacity:0.8;">Cổng thanh toán giả lập</p>
            </div>
            <h3 style="margin-bottom:1.5rem;">Quét mã MoMo để thanh toán</h3>
            <div style="background: white; padding: 1rem; border-radius: 12px; display: inline-block; margin-bottom: 1.5rem;">
                <img src="<?= $qrUrl ?>" alt="MoMo QR" style="width: 260px; height: 260px; border-radius: 8px; display: block;">
            </div>
            <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 8px; text-align: left; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.1);">
                <div style="margin-bottom: 0.6rem; display:flex; justify-content:space-between;">
                    <span class="text-muted">Chủ tài khoản:</span>
                    <strong><?= htmlspecialchars($bankOwner) ?></strong>
                </div>
                <div style="margin-bottom: 0.6rem; display:flex; justify-content:space-between;">
                    <span class="text-muted">STK / SĐT:</span>
                    <strong style="color:var(--primary);"><?= $bankAccNum ?></strong>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span class="text-muted">Số tiền cọc:</span>
                    <strong style="color:var(--red); font-size:1.15rem;"><?= number_format($amount, 0, ',', '.') ?> ₫</strong>
                </div>
            </div>
            
        <?php elseif ($method == 'bank'): ?>
            <!-- ATM / Chuyển khoản ngân hàng -->
            <div style="background: linear-gradient(135deg, #003087, #0052CC); color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
                <div style="font-size:2.5rem; margin-bottom:0.5rem;">🏦</div>
                <h2 style="margin:0; font-size:1.6rem;">Chuyển khoản ngân hàng</h2>
                <p style="opacity:0.8; margin:0.3rem 0 0;">ATM / Internet Banking / QR</p>
            </div>

            <!-- QR VietQR cho ATM -->
            <div style="background: white; padding: 1rem; border-radius: 12px; display: inline-block; margin-bottom: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <img src="<?= $qrUrl ?>" alt="VietQR ATM" style="width: 260px; height: 260px; border-radius: 8px; display: block;">
            </div>

            <p class="text-muted" style="font-size: 0.82rem; margin-bottom: 1.5rem;">
                📌 Quét bằng app ngân hàng hoặc nhập thủ công thông tin bên dưới
            </p>

            <div style="background: rgba(255,255,255,0.05); padding: 1.25rem; border-radius: 10px; text-align: left; margin-bottom: 2rem; border: 1px solid rgba(0,82,204,0.4);">
                <div style="margin-bottom: 0.75rem; display:flex; justify-content:space-between; align-items:center;">
                    <span class="text-muted">Ngân hàng:</span>
                    <strong>MB Bank (MBBank)</strong>
                </div>
                <div style="margin-bottom: 0.75rem; display:flex; justify-content:space-between; align-items:center;">
                    <span class="text-muted">Chủ tài khoản:</span>
                    <strong><?= htmlspecialchars($bankOwner) ?></strong>
                </div>
                <div style="margin-bottom: 0.75rem; display:flex; justify-content:space-between; align-items:center;">
                    <span class="text-muted">Số tài khoản:</span>
                    <strong style="color:var(--primary); font-size:1.2rem; font-family: monospace; letter-spacing:0.08em;"><?= $bankAccNum ?></strong>
                </div>
                <div style="margin-bottom: 0.75rem; display:flex; justify-content:space-between; align-items:center;">
                    <span class="text-muted">Số tiền cọc (5%):</span>
                    <strong style="color: #ff4d4d; font-size:1.25rem;"><?= number_format($amount, 0, ',', '.') ?> ₫</strong>
                </div>
                <div style="padding-top: 0.75rem; border-top: 1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center;">
                    <span class="text-muted">Nội dung CK:</span>
                    <strong style="color: var(--primary);"><?= htmlspecialchars($content) ?></strong>
                </div>
            </div>

            <div style="background: rgba(255,200,0,0.08); border: 1px solid rgba(255,200,0,0.3); border-radius: 8px; padding: 0.85rem; margin-bottom: 1.5rem; text-align: left;">
                <p style="margin: 0; font-size: 0.82rem; color: var(--gold);">
                    ⚠️ Vui lòng chuyển đúng số tiền và ghi đúng nội dung chuyển khoản để được xác nhận nhanh chóng.
                </p>
            </div>
        <?php endif; ?>

        <!-- Nút xác nhận (giả lập) -->
        <div style="border-top: 1px dashed var(--border); padding-top: 1.5rem; margin-top: 0.5rem;">
            <p class="text-muted" style="font-size:0.82rem; margin-bottom: 1rem;">
                Sau khi chuyển khoản thành công, nhấn nút xác nhận bên dưới để hoàn tất đặt lịch.
            </p>
            <a href="<?= BASE_URL ?>/Checkout/complete_payment/<?= $order->id ?>" class="btn btn-primary btn-block btn-lg">
                ✅ Tôi đã thanh toán – Xác nhận hoàn tất
            </a>
            <a href="<?= BASE_URL ?>/Checkout" class="btn text-muted mt-2">↩ Hủy giao dịch</a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
