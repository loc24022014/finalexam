<?php
$pageTitle = 'Trang Chủ - BanXe - Racing with your dream';
include 'app/views/shares/header.php';
?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="hero-content">
        <div class="hero-text-wrap">
            <div class="hero-badge" style="border-radius: 0; border: 1px solid var(--primary); padding: 0.5rem 1.5rem; letter-spacing: 0.2em; font-style: italic;"><span></span> BEYOND CONCRETE</div>
            <h1 class="hero-title" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 900; font-style: italic;">Đánh Thức<br><span class="accent">Bản Năng</span></h1>
            <p class="hero-desc" style="font-weight: 400; letter-spacing: 0.05em; font-style: italic; text-transform: uppercase; font-size: 0.9rem;">Khởi nguyên của tốc độ và sức mạnh thuần khiết. Thiết kế cắt vát góc cạnh đột phá cùng hiệu năng khí động học không khoan nhượng.</p>
            
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/Search/index" class="btn btn-primary btn-lg">Khám Phá Ngay</a>
                <a href="<?= BASE_URL ?>/Search/index?category_id=4" class="btn btn-outline btn-lg">⚡ Xe Điện</a>
            </div>
            
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-val"><span data-count="150">0</span><span>+</span></div>
                    <div class="hero-stat-label">Mẫu Xe Cao Cấp</div>
                </div>
                <div>
                    <div class="hero-stat-val"><span data-count="12">0</span><span>K+</span></div>
                    <div class="hero-stat-label">Khách Hàng Hài Lòng</div>
                </div>
                <div>
                    <div class="hero-stat-val"><span data-count="35">0</span><span>+</span></div>
                    <div class="hero-stat-label">Giải Thưởng Danh Giá</div>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-car-frame">
                <img src="<?= BASE_URL ?>/public/images/hero_car.png" alt="Showroom xe cao cấp">
                <div class="hero-float-badge">
                    <div class="hero-float-badge-icon">🏆</div>
                    <div class="hero-float-badge-text">
                        <strong>Premium Quality</strong>
                        <span>Đảm bảo tuyệt đối – Cam kết chất lượng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="categories-section">
    <div class="section" style="padding-top: 0; padding-bottom: 0;">
        <div class="section-header">
            <div>
                <span class="section-badge">DANH MỤC</span>
                <h2 class="section-title">Phân Khúc Xe</h2>
            </div>
        </div>
        
        <div class="categories-grid">
            <?php 
            $catIcons = ['🚙', '🚗', '🚐', '⚡', '🏎️'];
            $catIdx = 0;
            if (!empty($categories)):
                foreach ($categories as $cat): 
                    $icon = $catIcons[$catIdx % count($catIcons)];
                    $catIdx++;
            ?>
                <a href="<?= BASE_URL ?>/Search/index?category_id=<?= $cat->id ?>" class="cat-card">
                    <div class="cat-icon"><?= $icon ?></div>
                    <h3 class="cat-name"><?= htmlspecialchars($cat->name) ?></h3>
                    <div class="cat-count"><?= isset($cat->product_count) ? $cat->product_count : 0 ?> mẫu xe</div>
                </a>
            <?php 
                endforeach; 
            endif;
            ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <div>
            <span class="section-badge">XU HƯỚNG</span>
            <h2 class="section-title">Mẫu Xe Nổi Bật</h2>
        </div>
        <a href="<?= BASE_URL ?>/Search/index" class="btn btn-outline btn-sm">Xem tất cả →</a>
    </div>

    <div class="products-grid">
        <?php if (!empty($featuredProducts)): ?>
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-card-img-wrap">
                        <div class="product-card-img" style="display:flex; align-items:center; justify-content:center; color:var(--text-muted); background:rgba(255,255,255,0.03); font-size: 1.5rem;">
                            🚗
                        </div>
                        
                        <?php if ($product->brand): ?>
                            <span class="product-card-badge"><?= htmlspecialchars($product->brand) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-card-body">
                        <div class="product-card-cat"><?= htmlspecialchars($product->category_name ?? 'Phân khúc') ?></div>
                        <h3 class="product-card-name"><?= htmlspecialchars($product->name) ?></h3>
                        <p class="product-card-desc"><?= htmlspecialchars($product->description) ?></p>
                        
                        <div class="product-card-footer">
                            <div class="product-card-price">
                                <?= number_format($product->price, 0, ',', '.') ?> ₫
                            </div>
                            <div class="product-card-actions">
                                <a href="<?= BASE_URL ?>/Product/show/<?= $product->id ?>" class="btn-icon" title="Xem chi tiết">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <?php if (SessionHelper::isAdmin()): ?>
                                    <a href="<?= BASE_URL ?>/Product/edit/<?= $product->id ?>" class="btn-icon danger" title="Chỉnh sửa">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem 0; color: var(--text-muted);">
                Không có sản phẩm nào để hiển thị.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>