<?php
// app/controllers/ProductApiController.php
// CRUD API cho sản phẩm – bảo vệ bằng JWT (trừ GET)

require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/JwtHelper.php';

class ProductApiController {
    private $db;
    private $productModel;

    public function __construct() {
        $this->db           = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        header('Content-Type: application/json; charset=utf-8');
    }

    // ============================================================
    //  GET /api/product  – Công khai (không cần token)
    // ============================================================
    public function list() {
        $products = $this->productModel->getProducts();
        echo json_encode([
            'success' => true,
            'count'   => count($products),
            'data'    => $products
        ]);
        exit;
    }

    // ============================================================
    //  GET /api/product/{id}  – Công khai (không cần token)
    // ============================================================
    public function detail($id) {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode(['success' => true, 'data' => $product]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Product not found']);
        }
        exit;
    }

    // ============================================================
    //  POST /api/product  – 🔒 Yêu cầu JWT token
    // ============================================================
    public function create() {
        // Xác thực token – tự động trả 401 nếu không hợp lệ
        $auth = JwtHelper::requireAuth();

        $data        = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $name        = trim($data['name']        ?? '');
        $description = trim($data['description'] ?? '');
        $price       = $data['price']       ?? '';
        $category_id = $data['category_id'] ?? null;
        $brand       = trim($data['brand']   ?? '');

        if (empty($name) || empty($description) || $price === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc: name, description, price']);
            exit;
        }

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $brand);
        if ($result === true) {
            http_response_code(201);
            echo json_encode([
                'success'    => true,
                'message'    => 'Thêm sản phẩm thành công',
                'created_by' => $auth['username']
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $result]);
        }
        exit;
    }

    // ============================================================
    //  PUT /api/product/{id}  – 🔒 Yêu cầu JWT token
    // ============================================================
    public function update($id) {
        $auth = JwtHelper::requireAuth();

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) parse_str(file_get_contents('php://input'), $data);

        $name        = trim($data['name']        ?? $product->name);
        $description = trim($data['description'] ?? $product->description);
        $price       = $data['price']       ?? $product->price;
        $category_id = $data['category_id'] ?? $product->category_id;
        $brand       = trim($data['brand']   ?? $product->brand);

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $brand);
        if ($result === true) {
            echo json_encode([
                'success'    => true,
                'message'    => 'Cập nhật sản phẩm thành công',
                'updated_by' => $auth['username']
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
        }
        exit;
    }

    // ============================================================
    //  DELETE /api/product/{id}  – 🔒 Yêu cầu JWT + role admin
    // ============================================================
    public function delete($id) {
        $auth = JwtHelper::requireAuth();

        // Chỉ admin được xóa
        if ($auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Forbidden: Chỉ admin mới được xóa sản phẩm'
            ]);
            exit;
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode([
                'success'    => true,
                'message'    => 'Xóa sản phẩm thành công',
                'deleted_by' => $auth['username']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Xóa thất bại']);
        }
        exit;
    }
}
