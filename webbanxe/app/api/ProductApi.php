<?php
/**
 * app/api/ProductApi.php
 * ──────────────────────────────────────────────
 * GET    /api/product        → danh sách          🟢 công khai
 * GET    /api/product/{id}   → chi tiết            🟢 công khai
 * POST   /api/product        → thêm mới            🔒 cần token
 * PUT    /api/product/{id}   → cập nhật            🔒 cần token
 * DELETE /api/product/{id}   → xóa                 🔴 cần token + admin
 */

require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/JwtHelper.php';

class ProductApi {

    private ProductModel $model;
    private $db;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        $this->db    = (new Database())->getConnection();
        $this->model = new ProductModel($this->db);
    }

    // ── GET /api/product ────────────────────────── 🟢
    public function list(): void {
        $items = $this->model->getProducts();
        echo json_encode(['success' => true, 'count' => count($items), 'data' => $items]);
        exit;
    }

    // ── GET /api/product/{id} ────────────────────── 🟢
    public function detail(int $id): void {
        $item = $this->model->getProductById($id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $item]);
        exit;
    }

    // ── POST /api/product ────────────────────────── 🔒
    public function create(): void {
        $auth = JwtHelper::requireAuth();
        $d    = json_decode(file_get_contents('php://input'), true) ?: $_POST;

        $name = trim($d['name'] ?? '');
        $desc = trim($d['description'] ?? '');
        $price = $d['price'] ?? '';

        if (!$name || !$desc || $price === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thiếu trường bắt buộc: name, description, price']);
            exit;
        }

        $result = $this->model->addProduct(
            $name, $desc, $price,
            $d['category_id'] ?? null,
            trim($d['brand'] ?? '')
        );

        if ($result === true) {
            $newId = $this->db->lastInsertId();
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Them san pham thanh cong', 'id' => (int)$newId, 'created_by' => $auth['username']]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $result]);
        }
        exit;
    }

    // ── PUT /api/product/{id} ────────────────────── 🔒
    public function update(int $id): void {
        $auth = JwtHelper::requireAuth();
        $item = $this->model->getProductById($id);

        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }

        $d = json_decode(file_get_contents('php://input'), true) ?: [];

        $result = $this->model->updateProduct(
            $id,
            trim($d['name']        ?? $item->name),
            trim($d['description'] ?? $item->description),
            $d['price']            ?? $item->price,
            $d['category_id']      ?? $item->category_id,
            trim($d['brand']       ?? $item->brand)
        );

        echo json_encode($result === true
            ? ['success' => true,  'message' => 'Cập nhật thành công', 'updated_by' => $auth['username']]
            : ['success' => false, 'message' => 'Cập nhật thất bại']
        );
        exit;
    }

    // ── DELETE /api/product/{id} ─────────────────── 🔴 admin only
    public function delete(int $id): void {
        $auth = JwtHelper::requireAuth();

        if ($auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới được xóa sản phẩm']);
            exit;
        }

        if (!$this->model->getProductById($id)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }

        echo json_encode($this->model->deleteProduct($id)
            ? ['success' => true,  'message' => 'Xóa sản phẩm thành công', 'deleted_by' => $auth['username']]
            : ['success' => false, 'message' => 'Xóa thất bại']
        );
        exit;
    }
}
