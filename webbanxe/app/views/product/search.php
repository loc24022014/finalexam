<?php
$pageTitle = 'Tìm Kiếm Xe - BanXe';
include 'app/views/shares/header.php';

$keyword = $_GET['keyword'] ?? '';
$cat_id = $_GET['category_id'] ?? '';
$brand_selected = $_GET['brand'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
?>

<div class="search-page">
    <div class="search-hero">
        <h1>Khám Phá Dòng Xe Mơ Ước</h1>
        <form action="<?= BASE_URL ?>/Search/index" method="GET" class="search-form-big">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tên xe, dòng xe...">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Tìm kiếm
            </button>
        </form>
    </div>

    <div class="filter-panel">
        <h3>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Bộ Lọc Tìm Kiếm
        </h3>
        <form action="<?= BASE_URL ?>/Search/index" method="GET">
            <?php if(!empty($keyword)): ?>
                <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
            <?php endif; ?>
            
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Phân khúc xe</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Tất cả phân khúc --</option>
                        <?php if(!empty($categories_nav)): ?>
                            <?php foreach($categories_nav as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= $cat_id == $cat->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->name) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Hãng sản xuất</label>
                    <select name="brand" class="form-control">
                        <option value="">-- Tất cả hãng --</option>
                        <?php 
                        $brandsList = !empty($brands) ? $brands : ['Toyota', 'Ford', 'VinFast', 'Mercedes-Benz', 'BMW', 'Lamborghini'];
                        foreach($brandsList as $b): 
                        ?>
                            <option value="<?= htmlspecialchars($b) ?>" <?= $brand_selected === $b ? 'selected' : '' ?>><?= htmlspecialchars($b) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Khoảng giá (₫)</label>
                    <div class="price-range">
                        <input type="number" name="min_price" class="form-control" placeholder="Tối thiểu" value="<?= htmlspecialchars($min_price) ?>">
                        <span>-</span>
                        <input type="number" name="max_price" class="form-control" placeholder="Tối đa" value="<?= htmlspecialchars($max_price) ?>">
                    </div>
                </div>
                
                <div class="filter-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">Áp dụng</button>
                    <a href="<?= BASE_URL ?>/Search/index" class="btn btn-outline" style="padding: 0.65rem 1rem;">Xoá lọc</a>
                </div>
            </div>
        </form>
    </div>

    <div class="search-results-info">
        <span>Tìm thấy <strong><?= count($products ?? []) ?></strong> kết quả phù hợp</span>
    </div>

    <div class="products-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
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
            <div class="no-results" style="grid-column: 1/-1;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <h3>Không tìm thấy mẫu xe nào</h3>
                <p>Vui lòng thử lại với từ khóa hoặc bộ lọc khác.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>