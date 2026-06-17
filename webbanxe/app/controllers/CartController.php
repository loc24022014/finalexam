<?php
// app/controllers/CartController.php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

class CartController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        SessionHelper::start();
    }

    public function add($id) {
        SessionHelper::requireLogin();
        
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            SessionHelper::setFlash('error', 'Không tìm thấy xe.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            SessionHelper::setFlash('error', 'Xe này đã có trong danh sách đặt lịch của bạn.');
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'category_name' => $product->category_name
            ];
            SessionHelper::setFlash('success', 'Đã thêm xe vào danh sách đặt lịch hẹn.');
        }
        
        header('Location: ' . BASE_URL . '/Cart');
    }

    public function index() {
        SessionHelper::requireLogin();
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'];
        }
        // Tính cọc (mặc định cọc 5% giá trị xe để giữ chỗ)
        $deposit = $total * 0.05;
        
        include 'app/views/cart/index.php';
    }

    public function remove($id) {
        SessionHelper::requireLogin();
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            SessionHelper::setFlash('success', 'Đã xóa xe khỏi danh sách.');
        }
        header('Location: ' . BASE_URL . '/Cart');
    }
}
