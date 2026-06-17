<?php
require_once 'app/config/database.php';

try {
    $db = (new Database())->getConnection();
    
    // Kiểm tra tài khoản admin
    $stmtCheck = $db->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmtCheck->execute();
    
    if ($stmtCheck->rowCount() === 0) {
        echo "Tài khoản 'admin' không tồn tại trong cơ sở dữ liệu.";
        exit;
    }

    // Cập nhật mật khẩu thành 'password'
    $hashedPassword = password_hash('password', PASSWORD_BCRYPT);
    $stmtUpdate = $db->prepare("UPDATE users SET password = :password WHERE username = 'admin'");
    $stmtUpdate->bindParam(':password', $hashedPassword);
    $stmtUpdate->execute();
    
    echo "Đã cập nhật mật khẩu thành công. Tài khoản: admin | Mật khẩu mới: password";
} catch (Exception $e) {
    echo "Lỗi kết nối hoặc truy vấn: " . $e->getMessage();
}