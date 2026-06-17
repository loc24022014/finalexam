<?php
$pageTitle = 'Đăng Nhập - BanXe';
include 'app/views/shares/header.php';
?>

<div class="form-page">
    <div class="form-card">
        <div class="form-header">
            <div class="form-logo">BX</div>
            <h1 class="form-title">Đăng Nhập</h1>
            <p class="form-subtitle">Chào mừng trở lại! Vui lòng đăng nhập để tiếp tục.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/Auth/doLogin" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập..." required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Đăng Nhập</button>
            </div>
        </form>

        <div class="form-footer">
            Chưa có tài khoản? <a href="<?= BASE_URL ?>/Auth/register">Đăng ký ngay</a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>