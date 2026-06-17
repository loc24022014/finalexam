<?php
$pageTitle = 'Đăng Ký - BanXe';
include 'app/views/shares/header.php';
?>

<div class="form-page">
    <div class="form-card">
        <div class="form-header">
            <div class="form-logo">BX</div>
            <h1 class="form-title">Đăng Ký</h1>
            <p class="form-subtitle">Tạo tài khoản mới để trải nghiệm dịch vụ.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error" style="display: block;">
                <ul style="margin-left: 1.2rem; padding-left: 0;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/Auth/doRegister" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">Tên đăng nhập *</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập (ít nhất 3 ký tự)..." value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="example@banxe.vn" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="full_name">Họ & Tên</label>
                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Nguyễn Văn A" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Mật khẩu *</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự..." required>
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Vai trò *</label>
                <select id="role" name="role" class="form-control" style="padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); color: var(--text); border-radius: 8px; font-size: 0.95rem; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22%3E%3Cpolyline points=%226 9 12 15 18 9%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; cursor: pointer;">
                    <option value="user">👤 Người dùng</option>
                    <option value="admin">🛡️ Quản trị viên (Admin)</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Đăng Ký Tài Khoản</button>
            </div>
        </form>

        <div class="form-footer">
            Đã có tài khoản? <a href="<?= BASE_URL ?>/Auth/login">Đăng nhập ngay</a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>