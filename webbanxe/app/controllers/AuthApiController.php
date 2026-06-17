<?php
// app/controllers/AuthApiController.php
// Xử lý đăng nhập và cấp JWT token

require_once 'app/config/database.php';
require_once 'app/models/UserModel.php';
require_once 'app/helpers/JwtHelper.php';

class AuthApiController {

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
    }

    // ============================================================
    //  POST /api/auth/login
    //  Body JSON: { "username": "admin", "password": "password" }
    //  Trả về JWT token nếu đúng thông tin
    // ============================================================
    public function login() {
        // Chỉ cho phép POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed. Dùng POST.']);
            exit;
        }

        // Đọc body JSON (hoặc form-data)
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        // Validate đầu vào
        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập username và password'
            ]);
            exit;
        }

        // Kiểm tra user trong database
        $db        = (new Database())->getConnection();
        $userModel = new UserModel($db);
        $user      = $userModel->login($username, $password); // dùng method login() có sẵn

        if (!$user) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Sai username hoặc password'
            ]);
            exit;
        }

        // Tạo JWT token
        $token = JwtHelper::encode([
            'user_id'  => $user->id,
            'username' => $user->username,
            'email'    => $user->email,
            'role'     => $user->role,
        ]);

        // Trả về token
        echo json_encode([
            'success'    => true,
            'message'    => 'Đăng nhập thành công',
            'token'      => $token,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'user' => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
            ]
        ]);
        exit;
    }

    // ============================================================
    //  GET /api/auth/me
    //  Kiểm tra token hiện tại và trả về thông tin user
    // ============================================================
    public function me() {
        $payload = JwtHelper::requireAuth();

        echo json_encode([
            'success' => true,
            'message' => 'Token hợp lệ',
            'user'    => [
                'user_id'  => $payload['user_id'],
                'username' => $payload['username'],
                'email'    => $payload['email'],
                'role'     => $payload['role'],
            ],
            'token_info' => [
                'issued_at' => date('Y-m-d H:i:s', $payload['iat']),
                'expires_at'=> date('Y-m-d H:i:s', $payload['exp']),
            ]
        ]);
        exit;
    }
}
