<?php
$pageTitle = 'Danh sách đặt lịch - BanXe';
include 'app/views/shares/header.php';
?>

<div class="container py-5">
    <div class="header-section text-center mb-4">
        <h1 class="page-title">📅 Danh sách xe đặt lịch</h1>
        <p class="text-muted" style="margin-top:0.3rem;">Xem lại danh sách xe bạn muốn đặt lịch xem và đặt cọc.</p>
    </div>

    <!-- Progress Steps -->
    <div class="checkout-steps mb-5" style="display: flex; justify-content: center; align-items: center; gap: 1.5rem; font-family: 'Outfit', sans-serif; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
        <span style="color: var(--primary); border-bottom: 2px solid var(--primary); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--primary); color: var(--bg-darker); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">1</span>
            Chọn xe đặt lịch
        </span>
        <span style="color: var(--text-dim);">➔</span>
        <span style="color: var(--text-dim); display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--surface-solid); color: var(--text-muted); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">2</span>
            Lịch hẹn & Đặt cọc
        </span>
        <span style="color: var(--text-dim);">➔</span>
        <span style="color: var(--text-dim); display: flex; align-items: center; gap: 0.4rem;">
            <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--surface-solid); color: var(--text-muted); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">3</span>
            Hoàn tất
        </span>
    </div>

    <?php if (empty($cart)): ?>
        <div class="empty-state glass-card text-center py-5">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
            <h3>Danh sách trống</h3>
            <p class="text-muted">Bạn chưa chọn chiếc xe nào để đặt lịch.</p>
            <a href="<?= BASE_URL ?>/Search" class="btn btn-primary mt-3">Khám phá các dòng xe ngay</a>
        </div>
    <?php else: ?>
        <div class="cart-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Danh sách xe -->
            <div class="cart-items">
                <?php foreach ($cart as $id => $item): ?>
                    <div class="cart-item glass-card mb-3" style="display: flex; gap: 1.5rem; align-items: center; padding: 1rem;">
                        <img src="<?= htmlspecialchars(getImageUrl($item['image'] ?? '')) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" style="width: 150px; height: 100px; object-fit: cover; border-radius: 8px;">
                        <div class="cart-item-info" style="flex-grow: 1;">
                            <div class="text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars($item['category_name'] ?? 'Phân khúc xe') ?></div>
                            <h4 style="margin: 0.2rem 0; font-size: 1.2rem;"><?= htmlspecialchars($item['name']) ?></h4>
                            <div class="price" style="color: var(--primary); font-weight: 700; font-size: 1.1rem;">
                                <?= number_format($item['price'], 0, ',', '.') ?> ₫
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>/Cart/remove/<?= $id ?>" class="btn-icon" style="color: var(--red); background: rgba(255,59,48,0.1);" title="Xóa">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Tổng kết -->
            <div class="cart-summary">
                <div class="glass-card" style="padding: 1.5rem; position: sticky; top: 100px;">
                    <h3 style="margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Tổng quan</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span class="text-muted">Tổng giá trị xe:</span>
                        <span style="font-weight: 600;"><?= number_format($total, 0, ',', '.') ?> ₫</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; padding: 1rem; background: rgba(255, 204, 0, 0.1); border-radius: 8px;">
                        <div>
                            <div style="font-weight: 600; color: var(--primary);">Tiền cọc giữ xe (5%)</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">*Cọc để đảm bảo giữ xe trước khi xem</div>
                        </div>
                        <div style="font-size: 1.3rem; font-weight: 800; color: var(--primary); text-align: right;">
                            <?= number_format($deposit, 0, ',', '.') ?> ₫
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>/Checkout" class="btn btn-primary btn-block btn-lg" style="text-align: center;">
                        Tiếp tục Đặt lịch & Thanh toán cọc
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
