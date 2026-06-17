<?php
$pageTitle = 'Đặt lịch xem xe & Đặt cọc - BanXe';
include 'app/views/shares/header.php';
?>

<div class="container py-5">
    <div class="header-section text-center mb-4">
        <h1 class="page-title">Xác nhận thông tin & Đặt cọc</h1>
        <p class="text-muted" style="margin-top:0.3rem;">Vui lòng chọn ngày đến xem xe và phương thức thanh toán cọc giữ chỗ.</p>
    </div>

    <!-- Progress Steps -->
    <div class="checkout-steps mb-5" style="display: flex; justify-content: center; align-items: center; gap: 1.5rem; font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
        <span style="color: var(--text-dim); display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: rgba(0, 242, 254, 0.1); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; border: 1px solid var(--primary);">✓</span>
            Chọn xe đặt lịch
        </span>
        <span style="color: var(--text-dim);">➔</span>
        <span style="color: var(--secondary); border-bottom: 2px solid var(--secondary); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--secondary); color: var(--bg-darker); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">2</span>
            Lịch hẹn & Đặt cọc
        </span>
        <span style="color: var(--text-dim);">➔</span>
        <span style="color: var(--text-dim); display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--surface-solid); color: var(--text-muted); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">3</span>
            Hoàn tất
        </span>
    </div>

    <form action="<?= BASE_URL ?>/Checkout/process" method="POST">
        <div class="checkout-grid" style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 2rem;">
            
            <!-- Cột trái: Thông tin & Thanh toán -->
            <div class="checkout-form-area">
                
                <!-- Chọn ngày hẹn -->
                <div class="glass-card mb-4" style="padding: 1.5rem;">
                    <h3 style="margin-top:0; margin-bottom: 1rem;">📅 Chọn ngày hẹn xem xe</h3>
                    <p class="text-muted" style="font-size:0.9rem;">Hệ thống mặc định xếp lịch vào ngày mai để đảm bảo xe còn sẵn sàng.</p>
                    <div class="form-group" style="margin-top: 1rem;">
                        <input type="date" name="appointment_date" class="form-control" style="font-size: 1.1rem; padding: 0.8rem;" value="<?= $defaultDate ?>" required min="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="glass-card mb-4" style="padding: 1.5rem;">
                    <h3 style="margin-top:0; margin-bottom: 1.5rem;">💳 Phương thức thanh toán cọc</h3>
                    
                    <div class="payment-methods" style="display: flex; flex-direction: column; gap: 1rem;">
                        <label class="payment-method-label" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" name="payment_method" value="qr" checked>
                            <span style="font-size: 1.5rem;">📱</span>
                            <div style="flex-grow:1;">
                                <strong style="display:block;">Chuyển khoản QR (VietQR)</strong>
                                <span class="text-muted" style="font-size:0.85rem;">Quét mã QR qua ứng dụng ngân hàng</span>
                            </div>
                        </label>
                        
                        <label class="payment-method-label" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" name="payment_method" value="momo">
                            <span style="font-size: 1.5rem;">🌸</span>
                            <div style="flex-grow:1;">
                                <strong style="display:block;">Ví MoMo</strong>
                                <span class="text-muted" style="font-size:0.85rem;">Thanh toán an toàn qua ví điện tử MoMo</span>
                            </div>
                        </label>

                        <label class="payment-method-label" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" name="payment_method" value="bank">
                            <span style="font-size: 1.5rem;">🏦</span>
                            <div style="flex-grow:1;">
                                <strong style="display:block;">Thẻ ATM / Internet Banking</strong>
                                <span class="text-muted" style="font-size:0.85rem;">Thanh toán qua cổng Napas</span>
                            </div>
                        </label>
                        
                        <label class="payment-method-label" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                            <input type="radio" name="payment_method" value="cash">
                            <span style="font-size: 1.5rem;">💵</span>
                            <div style="flex-grow:1;">
                                <strong style="display:block;">Thanh toán tại Showroom</strong>
                                <span class="text-muted" style="font-size:0.85rem;">Đến trực tiếp cửa hàng để đóng cọc (Lưu ý: Không đảm bảo giữ xe 100% nếu có khách khác mua trước)</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Cột phải: Tổng kết đơn -->
            <div class="checkout-summary-area">
                <div class="glass-card" style="padding: 1.5rem; position: sticky; top: 100px;">
                    <h3 style="margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Chi tiết danh sách</h3>
                    
                    <div class="checkout-items" style="margin-bottom: 1.5rem;">
                        <?php foreach ($cart as $item): ?>
                            <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div>
                                    <div style="font-weight:500; font-size:1.05rem;"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="text-muted" style="font-size:0.85rem;">Giá: <?= number_format($item['price'], 0, ',', '.') ?> ₫</div>
                                </div>
                                <div style="width: 60px; height: 40px; border-radius:4px; background:rgba(255,255,255,0.05); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">🚗</div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span class="text-muted">Tổng giá trị xe:</span>
                            <span style="font-weight: 600;"><?= number_format($total, 0, ',', '.') ?> ₫</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding: 1rem; background: rgba(255, 204, 0, 0.1); border-radius: 8px;">
                            <div style="font-weight: 600; color: var(--primary);">Số tiền cọc cần thanh toán</div>
                            <div style="font-size: 1.3rem; font-weight: 800; color: var(--primary); text-align: right;">
                                <?= number_format($deposit, 0, ',', '.') ?> ₫
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="text-align: center; font-size:1.1rem; font-weight:600;">
                        Tiến Hành Đặt Lịch
                    </button>
                    <div class="text-center mt-3 text-muted" style="font-size:0.8rem;">
                        Bằng việc đặt lịch, bạn đồng ý với chính sách giữ chỗ của BanXe.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .payment-method-label input[type="radio"] {
        accent-color: var(--primary);
        width: 1.2rem;
        height: 1.2rem;
    }
    .payment-method-label:hover {
        border-color: var(--primary);
        background: rgba(255,204,0,0.05);
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>
