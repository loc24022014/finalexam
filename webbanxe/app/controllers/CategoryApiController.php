<?php
// app/controllers/CategoryApiController.php
// CRUD API cho danh mục – bảo vệ bằng JWT (trừ GET)

require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/JwtHelper.php';

class CategoryApiController {
    private $db;
    private $categoryModel;

    public function __construct() {
        $this->db            = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        header('Content-Type: application/json; charset=utf-8');
    }

    // ============================================================
    //  GET /api/category  – Công khai (không cần token)
    // ============================================================
    public function list() {
        $categories = $this->categoryModel->getCategories();
        echo json_encode([
            'success' => true,
            'count'   => count($categories),
            'data'    => $categories
        ]);
        exit;
    }

    // ============================================================
    //  GET /api/category/{id}  – Công khai (không cần token)
    // ============================================================
    public function detail($id) {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode(['success' => true, 'data' => $category]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Category not found']);
        }
        exit;
    }

    // ============================================================
    //  POST /api/category  – 🔒 Yêu cầu JWT token
    // ============================================================
    public function create() {
        $auth = JwtHelper::requireAuth();

        $data        = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $name        = trim($data['name']        ?? '');
        $description = trim($data['description'] ?? '');
        $slug        = trim($data['slug']        ?? '');

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên danh mục không được để trống']);
            exit;
        }

        $result = $this->categoryModel->addCategory($name, $description, $slug);
        if ($result === true) {
            http_response_code(201);
            echo json_encode([
                'success'    => true,
                'message'    => 'Thêm danh mục thành công',
                'created_by' => $auth['username']
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $result]);
        }
        exit;
    }

    // ============================================================
    //  PUT /api/category/{id}  – 🔒 Yêu cầu JWT token
    // ============================================================
    public function update($id) {
        $auth = JwtHelper::requireAuth();

        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) parse_str(file_get_contents('php://input'), $data);

        $name        = trim($data['name']        ?? $category->name);
        $description = trim($data['description'] ?? $category->description);
        $slug        = trim($data['slug']        ?? $category->slug);

        $result = $this->categoryModel->updateCategory($id, $name, $description, $slug);
        if ($result === true) {
            echo json_encode([
                'success'    => true,
                'message'    => 'Cập nhật danh mục thành công',
                'updated_by' => $auth['username']
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
        }
        exit;
    }

    // ============================================================
    //  DELETE /api/category/{id}  – 🔒 Yêu cầu JWT + role admin
    // ============================================================
    public function delete($id) {
        $auth = JwtHelper::requireAuth();

        // Chỉ admin được xóa danh mục
        if ($auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Forbidden: Chỉ admin mới được xóa danh mục'
            ]);
            exit;
        }

        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            echo json_encode([
                'success'    => true,
                'message'    => 'Xóa danh mục thành công',
                'deleted_by' => $auth['username']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Xóa thất bại']);
        }
        exit;
    }
}
