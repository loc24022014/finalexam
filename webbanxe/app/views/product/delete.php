<?php
$pageTitle = 'Xác Nhận Xoá Xe - Admin';
$activeMenu = 'products';
ob_start();
?>

<div class="admin-page">
    <div class="auth-wrapper" style="min-height: auto; padding: 2rem 0;">
        <div class="auth-card glass-card animate-fade-in text-center max-w-xl mx-auto" style="padding: 3rem 2rem; border: 1px solid var(--border-strong);">
            <div class="delete-icon-wrap" style="font-size: 4rem; margin-bottom: 1.5rem;">
                ⚠️
            </div>
            <h2 class="auth-title" style="color: var(--red); font-family: var(--font-heading); font-style: italic; text-transform: uppercase;">Xác Nhận Xoá <span>Xe</span></h2>
            <p class="auth-subtitle mb-4" style="color: var(--text-muted); font-size: 0.95rem;">Hành động này không thể hoàn tác. Bạn có chắc chắn muốn xoá chiếc xe này khỏi hệ thống?</p>

            <div class="delete-product-details glass-card mb-4" style="background: rgba(255,255,255,0.02); text-align: left; padding: 1.5rem; border: 1px solid var(--border);">
                <div class="d-flex gap-3 align-center">
                    <div style="width: 80px; height: 80px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); font-size: 1.5rem;">
                        🚗
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: var(--red); text-transform: uppercase; font-weight: 600; letter-spacing: 1px;"><?= htmlspecialchars($product->brand ?? '') ?></span>
                        <h4 style="font-family: var(--font-heading); font-size: 1.2rem; margin: 0.1rem 0; font-style: italic; text-transform: uppercase;"><?= htmlspecialchars($product->name) ?></h4>
                        <span style="color: var(--yellow); font-weight: 700; font-size: 1.1rem;"><?= number_format($product->price, 0, ',', '.') ?> ₫</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-center gap-3">
                <a href="<?= BASE_URL ?>/Product" class="btn btn-outline" style="min-width: 120px;">Huỷ</a>
                <a href="<?= BASE_URL ?>/Product/delete/<?= $product->id ?>" class="btn btn-primary" style="background: var(--red); border-color: var(--red); color: #fff; min-width: 120px;">Xác Nhận Xoá</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>
