<?php
/**
 * app/api/CartApi.php – v2 (Database-backed, stateless)
 * ──────────────────────────────────────────────────────
 * Giỏ hàng lưu trong bảng `cart_items` (tạo tự động).
 * Mỗi user có 1 giỏ riêng, nhận biết qua JWT user_id.
 *
 * GET    /api/cart               → xem giỏ hàng   🔒
 * POST   /api/cart               → thêm xe         🔒
 * DELETE /api/cart/{product_id}  → xóa 1 xe        🔒
 * DELETE /api/cart               → xóa toàn bộ     🔒
 */

require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/JwtHelper.php';

class CartApi {

    private $db;
    private ProductModel $products;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        $this->db       = (new Database())->getConnection();
        $this->products = new ProductModel($this->db);
        $this->ensureTable();
    }

    // Tạo bảng cart_items nếu chưa có
    private function ensureTable(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `cart_items` (
                `id`         INT AUTO_INCREMENT PRIMARY KEY,
                `user_id`    INT NOT NULL,
                `product_id` INT NOT NULL,
                `added_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY `uq_user_product` (`user_id`, `product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    // ── Lấy giỏ hàng từ DB theo user_id ──────────────────
    private function getCartItems(int $user_id): array {
        $stmt = $this->db->prepare(
            "SELECT ci.product_id, p.name, p.price, p.brand,
                    c.name AS category_name
             FROM cart_items ci
             JOIN product p   ON p.id = ci.product_id
             LEFT JOIN category c ON c.id = p.category_id
             WHERE ci.user_id = :uid"
        );
        $stmt->bindParam(':uid', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildSummary(int $user_id): array {
        $items = $this->getCartItems($user_id);
        $total = array_sum(array_column($items, 'price'));
        return [
            'items'   => $items,
            'count'   => count($items),
            'total'   => (float)$total,
            'deposit' => round($total * 0.05),
        ];
    }

    // ── GET /api/cart ────────────────────────────── 🔒
    public function list(): void {
        $auth = JwtHelper::requireAuth();
        echo json_encode(['success' => true] + $this->buildSummary($auth['user_id']));
        exit;
    }

    // ── POST /api/cart ───────────────────────────── 🔒
    public function add(): void {
        $auth = JwtHelper::requireAuth();
        $d    = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $pid  = (int)($d['product_id'] ?? 0);

        if (!$pid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thieu product_id']);
            exit;
        }

        $product = $this->products->getProductById($pid);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'San pham khong ton tai']);
            exit;
        }

        // INSERT IGNORE để tránh duplicate
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO cart_items (user_id, product_id) VALUES (:uid, :pid)"
        );
        $stmt->bindParam(':uid', $auth['user_id']);
        $stmt->bindParam(':pid', $pid);
        $stmt->execute();

        $added = $stmt->rowCount() > 0;
        $msg   = $added ? 'Da them xe vao gio hang' : 'Xe nay da co trong gio hang';

        echo json_encode(['success' => true, 'message' => $msg] + $this->buildSummary($auth['user_id']));
        exit;
    }

    // ── DELETE /api/cart/{product_id} ───────────── 🔒
    public function remove(int $pid): void {
        $auth = JwtHelper::requireAuth();
        $stmt = $this->db->prepare(
            "DELETE FROM cart_items WHERE user_id = :uid AND product_id = :pid"
        );
        $stmt->bindParam(':uid', $auth['user_id']);
        $stmt->bindParam(':pid', $pid);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Da xoa khoi gio hang'] + $this->buildSummary($auth['user_id']));
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'San pham khong co trong gio hang']);
        }
        exit;
    }

    // ── DELETE /api/cart (xóa hết) ──────────────── 🔒
    public function clear(): void {
        $auth = JwtHelper::requireAuth();
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE user_id = :uid");
        $stmt->bindParam(':uid', $auth['user_id']);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Da xoa toan bo gio hang', 'count' => 0, 'items' => [], 'total' => 0, 'deposit' => 0]);
        exit;
    }

    // ── Helper cho OrderApi: lấy cart và xóa sau checkout ──
    public function getCartForCheckout(int $user_id): array {
        return $this->getCartItems($user_id);
    }

    public function clearByUser(int $user_id): void {
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE user_id = :uid");
        $stmt->bindParam(':uid', $user_id);
        $stmt->execute();
    }
}
