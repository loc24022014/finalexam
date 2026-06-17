<?php
require_once 'app/helpers/SessionHelper.php';
SessionHelper::start();
$categories_nav = $GLOBALS['categories_nav'] ?? [];
$success_flash  = SessionHelper::getFlash('success');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Loc_Car_1273 - Mua bán xe ô tô chính hãng, giá tốt nhất. Racing with your dream.">
<title><?= $pageTitle ?? 'Loc_Car_1273 - Racing with your dream' ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=<?= time() ?>">
<script>const BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>

<!-- Toast Flash Message -->
<?php if ($success_flash): ?>
<div class="toast-container">
    <div class="toast success">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="toast-icon"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span class="toast-msg"><?= htmlspecialchars($success_flash) ?></span>
    </div>
</div>
<?php endif; ?>

<nav class="navbar" id="main-navbar">
    <!-- Brand -->
    <a href="<?= BASE_URL ?>/" class="navbar-brand">
        <div class="brand-logo">LC</div>
        <div class="brand-text">
            <span class="brand-name">Loc_Car_1273</span>
            <span class="brand-tagline">Racing with your dream</span>
        </div>
    </a>

    <!-- Nav -->
    <ul class="navbar-nav">
        <!-- Sản Phẩm dropdown -->
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/Search/index">
                Sản Phẩm
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-left">
                    <?php
                    $catIcons = ['🚙','🚗','🚐','⚡','🏎️'];
                    $catIdx   = 0;
                    if (!empty($categories_nav)):
                        foreach ($categories_nav as $cat):
                            $icon = $catIcons[$catIdx % count($catIcons)]; $catIdx++;
                    ?>
                    <a href="<?= BASE_URL ?>/Search/index?category_id=<?= $cat->id ?>" class="dropdown-cat-item">
                        <span><?= $icon ?> <?= htmlspecialchars($cat->name) ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                    <?php endforeach; endif; ?>
                    <a href="<?= BASE_URL ?>/Search/index" class="dropdown-cat-item" style="margin-top:0.5rem; color: var(--red);">
                        🔍 Xem tất cả xe
                    </a>
                </div>
                <div class="dropdown-right">
                    <div class="dropdown-right-header">
                        <div>
                            <div style="font-size:3rem;">🏎️</div>
                        </div>
                        <div class="dropdown-right-header-text">
                            <h4>Khám phá dòng xe cao cấp</h4>
                            <p>Trải nghiệm cảm giác lái tuyệt vời với bộ sưu tập xe đẳng cấp</p>
                            <a href="<?= BASE_URL ?>/Search/index" class="btn-sm">Chọn xe ngay →</a>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>/Search/index?brand=Toyota" class="dropdown-car-card">
                        <div style="font-size:2rem;">🚗</div>
                        <div class="dropdown-car-card-info">
                            <strong>Toyota</strong>
                            <span>Sedan · SUV</span>
                        </div>
                    </a>
                    <a href="<?= BASE_URL ?>/Search/index?brand=BMW" class="dropdown-car-card">
                        <div style="font-size:2rem;">🚙</div>
                        <div class="dropdown-car-card-info">
                            <strong>BMW</strong>
                            <span>Luxury · Sport</span>
                        </div>
                    </a>
                    <a href="<?= BASE_URL ?>/Search/index?brand=Mercedes-Benz" class="dropdown-car-card">
                        <div style="font-size:2rem;">⭐</div>
                        <div class="dropdown-car-card-info">
                            <strong>Mercedes-Benz</strong>
                            <span>Premium sedan</span>
                        </div>
                    </a>
                    <a href="<?= BASE_URL ?>/Search/index?brand=VinFast" class="dropdown-car-card">
                        <div style="font-size:2rem;">⚡</div>
                        <div class="dropdown-car-card-info">
                            <strong>VinFast</strong>
                            <span>Xe điện VN</span>
                        </div>
                    </a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/Search/index">Mua Xe</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/Service">
                Dịch vụ &amp; Hỗ trợ
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
            <div class="dropdown-menu" style="min-width:220px;">
                <div class="dropdown-left" style="width:100%;">
                    <a href="<?= BASE_URL ?>/Service" class="dropdown-cat-item">🔧 Bảo dưỡng & Sửa chữa</a>
                    <a href="<?= BASE_URL ?>/Service" class="dropdown-cat-item">💳 Hỗ trợ tài chính</a>
                    <a href="<?= BASE_URL ?>/Service" class="dropdown-cat-item">🛡️ Bảo hiểm xe</a>
                    <a href="<?= BASE_URL ?>/Service" class="dropdown-cat-item">🚚 Giao xe tận nơi</a>
                    <div style="height:1px; background: var(--border); margin: 0.3rem 0;"></div>
                    <a href="<?= BASE_URL ?>/Service#contact" class="dropdown-cat-item" style="color: var(--primary);">📞 Liên hệ hỗ trợ</a>
                </div>
            </div>
        </li>

        <?php if (SessionHelper::isAdmin()): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/Admin" style="color: var(--primary);">
                Trang Quản Trị
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Search -->
    <form class="navbar-search" id="navbar-search-form">
        <input type="text" id="navbar-search-input" placeholder="Tìm kiếm xe..." autocomplete="off">
        <button type="submit" class="search-btn" title="Tìm kiếm">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>
    </form>

    <!-- Right -->
    <div class="navbar-right" style="display: flex; align-items: center;">
        <?php if (SessionHelper::isLoggedIn()): ?>
        <a href="<?= BASE_URL ?>/Cart" title="Danh sách đặt lịch" style="position:relative; margin-right: 1.5rem; color: var(--text); display: flex; align-items: center; text-decoration: none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:24px;height:24px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span style="position:absolute; top:-8px; right:-10px; background:var(--primary); color:#000; font-size:11px; font-weight:800; padding:2px 6px; border-radius:10px;"><?= count($_SESSION['cart']) ?></span>
            <?php endif; ?>
        </a>
        <div class="user-menu">
            <button class="user-btn">
                <div class="user-avatar"><?= strtoupper(substr(SessionHelper::get('user_username'), 0, 1)) ?></div>
                <span class="user-name"><?= htmlspecialchars(SessionHelper::get('user_fullname') ?: SessionHelper::get('user_username')) ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:12px;"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="user-dropdown">
                <?php if (SessionHelper::isAdmin()): ?>
                <a href="<?= BASE_URL ?>/Admin">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Bảng điều khiển
                </a>
                <?php endif; ?>
                <div class="divider"></div>
                <a href="<?= BASE_URL ?>/Auth/logout" style="color:#fca5a5;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Đăng xuất
                </a>
            </div>
        </div>
        <?php else: ?>
        <a href="<?= BASE_URL ?>/Auth/login" class="btn btn-primary btn-sm">Đăng nhập</a>
        <?php endif; ?>
    </div>
</nav>