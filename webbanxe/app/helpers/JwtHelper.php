<?php
// app/helpers/JwtHelper.php
// JWT thuần PHP – không cần thư viện ngoài
// Thuật toán: HMAC-SHA256

class JwtHelper {

    // ---------------------------------------------------------------
    // Khoá bí mật – đổi thành chuỗi ngẫu nhiên dài ở môi trường thật
    // ---------------------------------------------------------------
    private static $secret = 'WebBanXe@SecretKey#2024!ChangeMe';

    // Thời gian hiệu lực token (giây) – mặc định 1 giờ
    private static $ttl = 3600;

    // ==============================================================
    //  ENCODE – Tạo JWT từ payload
    // ==============================================================
    public static function encode(array $payload): string {
        // 1. Header
        $header = self::b64url(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]));

        // 2. Payload – thêm thời điểm tạo (iat) và hết hạn (exp)
        $payload['iat'] = time();
        $payload['exp'] = time() + self::$ttl;
        $body = self::b64url(json_encode($payload));

        // 3. Chữ ký – HMAC-SHA256
        $signature = self::b64url(
            hash_hmac('sha256', "$header.$body", self::$secret, true)
        );

        return "$header.$body.$signature";
    }

    // ==============================================================
    //  DECODE – Giải mã và xác thực JWT
    //  Trả về array payload nếu hợp lệ, false nếu không hợp lệ
    // ==============================================================
    public static function decode(string $token) {
        $parts = explode('.', $token);

        // JWT phải có đúng 3 phần
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $body, $signature] = $parts;

        // Xác thực chữ ký
        $expected = self::b64url(
            hash_hmac('sha256', "$header.$body", self::$secret, true)
        );

        // Dùng hash_equals để tránh timing attack
        if (!hash_equals($expected, $signature)) {
            return false;
        }

        // Giải mã payload
        $payload = json_decode(self::b64urlDecode($body), true);

        // Kiểm tra token đã hết hạn chưa
        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            return false; // Token hết hạn
        }

        return $payload;
    }

    // ==============================================================
    //  Lấy token từ request header Authorization: Bearer <token>
    // ==============================================================
    public static function getTokenFromRequest(): ?string {
        // Cách 1: Apache / Nginx với getallheaders()
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'authorization') {
                    if (preg_match('/^Bearer\s+(.+)$/i', $value, $m)) {
                        return $m[1];
                    }
                }
            }
        }

        // Cách 2: PHP built-in server / CGI
        $auth = $_SERVER['HTTP_AUTHORIZATION']
             ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
             ?? '';

        if (preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) {
            return $m[1];
        }

        return null;
    }

    // ==============================================================
    //  Middleware – Xác thực token, tự động trả 401 nếu không hợp lệ
    //  Trả về payload nếu hợp lệ
    // ==============================================================
    public static function requireAuth(): array {
        header('Content-Type: application/json; charset=utf-8');

        $token = self::getTokenFromRequest();

        if (!$token) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized: Token không tồn tại. Vui lòng đăng nhập.',
                'hint'    => 'Thêm header: Authorization: Bearer <token>'
            ]);
            exit;
        }

        $payload = self::decode($token);

        if (!$payload) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized: Token không hợp lệ hoặc đã hết hạn.',
                'hint'    => 'Hãy đăng nhập lại qua POST /api/auth/login'
            ]);
            exit;
        }

        return $payload;
    }

    // ==============================================================
    //  Helpers – Base64 URL encode / decode (chuẩn JWT)
    // ==============================================================
    private static function b64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function b64urlDecode(string $data): string {
        $padded = $data . str_repeat('=', (4 - strlen($data) % 4) % 4);
        return base64_decode(strtr($padded, '-_', '+/'));
    }
}
