<?php
// app/models/UserModel.php
class UserModel {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function register($username, $email, $password, $fullName = '', $role = 'user') {
        $errors = [];
        if (empty($username)) $errors['username'] = 'Tên đăng nhập không được để trống.';
        if (strlen($username) < 3) $errors['username'] = 'Tên đăng nhập ít nhất 3 ký tự.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email không hợp lệ.';
        if (empty($password) || strlen($password) < 6) $errors['password'] = 'Mật khẩu ít nhất 6 ký tự.';

        if (!empty($errors)) return $errors;

        if ($this->findByUsername($username)) {
            return ['username' => 'Tên đăng nhập đã tồn tại.'];
        }
        if ($this->findByEmail($email)) {
            return ['email' => 'Email đã được sử dụng.'];
        }

        $allowedRoles = ['user', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            $role = 'user';
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (username, email, password, full_name, role) VALUES (:u, :e, :p, :fn, :role)"
        );
        $stmt->bindParam(':u', $username);
        $stmt->bindParam(':e', $email);
        $stmt->bindParam(':p', $hashed);
        $stmt->bindParam(':fn', $fullName);
        $stmt->bindParam(':role', $role);
        if ($stmt->execute()) return true;
        return ['general' => 'Đã có lỗi xảy ra, vui lòng thử lại.'];
    }

    public function login($username, $password) {
        $user = $this->findByUsername($username);
        if (!$user) return false;
        if (!password_verify($password, $user->password)) return false;
        return $user;
    }
}
