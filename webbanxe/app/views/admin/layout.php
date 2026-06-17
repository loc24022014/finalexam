<?php
require_once 'app/helpers/SessionHelper.php';
SessionHelper::start();
SessionHelper::requireAdmin();
$success_flash = SessionHelper::getFlash('success');
$error_flash = SessionHelper::getFlash('error');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Admin Dashboard' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=<?= time() ?>">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    body {
        margin: 0;
        padding: 0;
        background: var(--bg-darker);
        color: var(--text);
        display: flex;
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }
    .admin-sidebar {
        width: 260px;
        background: var(--surface-solid);
        border-right: 1px solid var(--border-strong);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0; bottom: 0; left: 0;
        z-index: 100;
    }
    .admin-brand {
        padding: 2rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--border-strong);
        text-decoration: none;
    }
    .admin-menu {
        flex: 1;
        padding: 1.5rem 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        overflow-y: auto;
    }
    .admin-menu-item {
        padding: 0.8rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-style: italic;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }
    .admin-menu-item:hover, .admin-menu-item.active {
        color: var(--primary);
        background: rgba(234, 179, 8, 0.05);
        border-left-color: var(--primary);
    }
    .admin-content {
        flex: 1;
        margin-left: 260px;
        display: flex;
        flex-direction: column;
    }
    .admin-topbar {
        height: 70px;
        background: rgba(0,0,0,0.8);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-strong);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0 2rem;
        position: sticky;
        top: 0;
        z-index: 99;
    }
    .admin-main {
        padding: 2.5rem;
        flex: 1;
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .stat-card {
        background: transparent;
        border: 1px solid var(--border-strong);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);
    }
    .stat-icon {
        width: 50px; height: 50px;
        background: var(--primary);
        color: #000;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);
    }
    .stat-info h4 {
        margin: 0; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;
    }
    .stat-info .val {
        font-size: 1.8rem; font-weight: 900; font-style: italic; color: var(--text); margin-top: 0.3rem;
    }
</style>
</head>
<body>

<?php if ($success_flash): ?>
<div class="toast-container"><div class="toast success"><span class="toast-msg"><?= htmlspecialchars($success_flash) ?></span></div></div>
<?php endif; ?>
<?php if ($error_flash): ?>
<div class="toast-container"><div class="toast" style="border-left-color:red;"><span class="toast-msg"><?= htmlspecialchars($error_flash) ?></span></div></div>
<?php endif; ?>

<aside class="admin-sidebar">
    <a href="<?= BASE_URL ?>/" class="admin-brand">
        <div class="brand-logo" style="font-size: 1.5rem;">LC</div>
        <div class="brand-name" style="font-size: 1rem;">Admin Panel</div>
    </a>
    <nav class="admin-menu">
        <a href="<?= BASE_URL ?>/Admin" class="admin-menu-item <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
            Tổng quan
        </a>
        <a href="<?= BASE_URL ?>/Admin/orders" class="admin-menu-item <?= ($activeMenu ?? '') == 'orders' ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Đặt lịch
        </a>
        <a href="<?= BASE_URL ?>/Product" class="admin-menu-item <?= ($activeMenu ?? '') == 'products' ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;"><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17H3v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0H9"/></svg>
            Sản phẩm
        </a>
        <a href="<?= BASE_URL ?>/Category/list" class="admin-menu-item <?= ($activeMenu ?? '') == 'categories' ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Phân khúc
        </a>
        <a href="<?= BASE_URL ?>/Admin/users" class="admin-menu-item <?= ($activeMenu ?? '') == 'users' ? 'active' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Khách hàng
        </a>
        <div style="flex:1;"></div>
        <a href="<?= BASE_URL ?>/" class="admin-menu-item" style="color:var(--text-dim);">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Về trang chính
        </a>
    </nav>
</aside>

<div class="admin-content">
    <header class="admin-topbar">
        <div class="user-menu" style="display:flex; align-items:center; gap:1rem;">
            <div class="user-avatar" style="width:36px;height:36px;"><?= strtoupper(substr(SessionHelper::get('user_username'), 0, 1)) ?></div>
            <span style="font-weight:700; font-style:italic;"><?= SessionHelper::get('user_username') ?></span>
        </div>
    </header>
    
    <main class="admin-main">
        <?= $content ?? '' ?>
    </main>
</div>

</body>
</html>
