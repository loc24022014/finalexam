<?php
$pageTitle = htmlspecialchars($product->name) . ' - BanXe';
include 'app/views/shares/header.php';
?>

<div class="container py-5">
    <!-- Breadcrumbs / Back button -->
    <div class="back-link-wrap">
        <a href="<?= BASE_URL ?>/Product" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;margin-right:0.5rem;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Quay lại danh sách xe
        </a>
    </div>

    <!-- Product Details Section -->
    <div class="product-details-wrap mt-4">
        <div class="product-details-grid">
            
            <!-- Left: Image Wrap -->
            <div class="product-details-img-area glass-card">
                <div class="product-main-no-img">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    <span>Không có hình ảnh</span>
                </div>
            </div>

            <!-- Right: Info Area -->
            <div class="product-details-info-area">
                <div class="details-header">
                    <?php if ($product->brand): ?>
                        <span class="details-brand-badge"><?= htmlspecialchars($product->brand) ?></span>
                    <?php endif; ?>
                    <span class="details-category-badge"><?= htmlspecialchars($product->category_name) ?></span>
                    <h1 class="details-title"><?= htmlspecialchars($product->name) ?></h1>
                </div>

                <div class="details-price-card glass-card">
                    <span class="details-price-label">Giá niêm yết chính hãng</span>
                    <h2 class="details-price-value"><?= number_format($product->price, 0, ',', '.') ?> ₫</h2>
                    <p class="details-price-tax">* Giá đã bao gồm thuế GTGT (VAT) và các thuế nhập khẩu liên quan.</p>
                </div>

                <div class="details-highlights mt-4">
                    <div class="highlight-item">
                        <span class="highlight-icon">🏎️</span>
                        <div class="highlight-text">
                            <strong>Thương hiệu uy tín</strong>
                            <span>Nhập khẩu chính hãng</span>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-icon">🛡️</span>
                        <div class="highlight-text">
                            <strong>Bảo hành chính hãng</strong>
                            <span>5 năm hoặc 150.000 km</span>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <span class="highlight-icon">⚡</span>
                        <div class="highlight-text">
                            <strong>Hỗ trợ kỹ thuật</strong>
                            <span>Cứu hộ 24/7 toàn quốc</span>
                        </div>
                    </div>
                </div>

                <div class="details-actions mt-4">
                    <a href="<?= BASE_URL ?>/Cart/add/<?= $product->id ?>" class="btn btn-primary btn-lg btn-block btn-buy-now" style="text-align: center;">
                        📅 Đặt Lịch Xem & Đặt Cọc
                    </a>
                    <p class="text-center text-muted mt-2" style="font-size: 0.8rem; letter-spacing: 0.02em;">
                        * Chỉ cần thanh toán trước <strong>5% giá trị xe</strong> làm tiền cọc để đảm bảo giữ xe trước khi xem.
                    </p>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <div class="admin-details-actions d-flex gap-2 mt-3">
                            <a href="<?= BASE_URL ?>/Product/edit/<?= $product->id ?>" class="btn btn-warning flex-grow text-center" style="display:inline-block;">
                                📝 Chỉnh sửa thông tin
                            </a>
                            <a href="<?= BASE_URL ?>/Product/confirmDelete/<?= $product->id ?>" class="btn btn-danger flex-grow text-center" style="display:inline-block;">
                                🗑️ Xoá xe khỏi hệ thống
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Description Box -->
        <div class="details-desc-box glass-card mt-5">
            <h3 class="desc-box-title">Thông Số & Mô Tả Chi Tiết</h3>
            <div class="desc-box-content mt-3">
                <?= nl2br(htmlspecialchars($product->description)) ?>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
