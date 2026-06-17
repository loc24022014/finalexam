<?php
/**
 * app/api/AuthApi.php
 * ──────────────────────────────────────────────
 * Endpoint: POST /api/auth/login  → đăng nhập, trả JWT
 *           GET  /api/auth/me     → xem thông tin user hiện tại
 */

require_once 'app/config/database.php';
require_once 'app/models/UserModel.php';
require_once 'app/helpers/JwtHelper.php';

class AuthApi {

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
    }

    // POST /api/auth/login
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            exit;
        }

        $data     = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if (!$username || !$password) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập username và password']);
            exit;
        }

        $db   = (new Database())->getConnection();
        $user = (new UserModel($db))->login($username, $password);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Sai username hoặc password']);
            exit;
        }

        $token = JwtHelper::encode([
            'user_id'  => $user->id,
            'username' => $user->username,
            'email'    => $user->email,
            'role'     => $user->role,
        ]);

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
            ],
        ]);
        exit;
    }

    // GET /api/auth/me  (🔒 cần token)
    public function me(): void {
        $payload = JwtHelper::requireAuth();
        echo json_encode([
            'success' => true,
            'user' => [
                'user_id'  => $payload['user_id'],
                'username' => $payload['username'],
                'email'    => $payload['email'],
                'role'     => $payload['role'],
            ],
            'token_info' => [
                'issued_at'  => date('Y-m-d H:i:s', $payload['iat']),
                'expires_at' => date('Y-m-d H:i:s', $payload['exp']),
            ],
        ]);
        exit;
    }
}
