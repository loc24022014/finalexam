<?php
/**
 * app/api/CategoryApi.php
 * ──────────────────────────────────────────────
 * GET    /api/category        → danh sách         🟢 công khai
 * GET    /api/category/{id}   → chi tiết           🟢 công khai
 * POST   /api/category        → thêm mới           🔒 cần token
 * PUT    /api/category/{id}   → cập nhật           🔒 cần token
 * DELETE /api/category/{id}   → xóa                🔴 cần token + admin
 */

require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/JwtHelper.php';

class CategoryApi {

    private CategoryModel $model;
    private $db;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        $this->db    = (new Database())->getConnection();
        $this->model = new CategoryModel($this->db);
    }

    // ── GET /api/category ───────────────────────── 🟢
    public function list(): void {
        $items = $this->model->getCategories();
        echo json_encode(['success' => true, 'count' => count($items), 'data' => $items]);
        exit;
    }

    // ── GET /api/category/{id} ──────────────────── 🟢
    public function detail(int $id): void {
        $item = $this->model->getCategoryById($id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Danh mục không tồn tại']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $item]);
        exit;
    }

    // ── POST /api/category ──────────────────────── 🔒
    public function create(): void {
        $auth = JwtHelper::requireAuth();
        $d    = json_decode(file_get_contents('php://input'), true) ?: $_POST;

        $name = trim($d['name'] ?? '');
        if (!$name) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên danh mục không được để trống']);
            exit;
        }

        $result = $this->model->addCategory(
            $name,
            trim($d['description'] ?? ''),
            trim($d['slug'] ?? '')
        );

        if ($result === true) {
            $newId = $this->db->lastInsertId();
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Them danh muc thanh cong', 'id' => (int)$newId, 'created_by' => $auth['username']]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $result]);
        }
        exit;
    }

    // ── PUT /api/category/{id} ──────────────────── 🔒
    public function update(int $id): void {
        $auth = JwtHelper::requireAuth();
        $item = $this->model->getCategoryById($id);

        if (!$item) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Danh mục không tồn tại']);
            exit;
        }

        $d = json_decode(file_get_contents('php://input'), true) ?: [];

        $result = $this->model->updateCategory(
            $id,
            trim($d['name']        ?? $item->name),
            trim($d['description'] ?? $item->description),
            trim($d['slug']        ?? $item->slug)
        );

        echo json_encode($result === true
            ? ['success' => true,  'message' => 'Cập nhật thành công', 'updated_by' => $auth['username']]
            : ['success' => false, 'message' => 'Cập nhật thất bại']
        );
        exit;
    }

    // ── DELETE /api/category/{id} ───────────────── 🔴 admin only
    public function delete(int $id): void {
        $auth = JwtHelper::requireAuth();

        if ($auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới được xóa danh mục']);
            exit;
        }

        if (!$this->model->getCategoryById($id)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Danh mục không tồn tại']);
            exit;
        }

        echo json_encode($this->model->deleteCategory($id)
            ? ['success' => true,  'message' => 'Xóa danh mục thành công', 'deleted_by' => $auth['username']]
            : ['success' => false, 'message' => 'Xóa thất bại']
        );
        exit;
    }
}
