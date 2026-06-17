<?php
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

class AdminController {
    private $db;
    private $orderModel;
    private $productModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
        $this->productModel = new ProductModel($this->db);
        SessionHelper::start();
        SessionHelper::requireAdmin();
    }

    public function index() {
        $activeMenu = 'dashboard';
        $pageTitle = 'Tổng Quan - Admin';

        // Get basic stats
        // Total products
        $stmt = $this->db->query("SELECT COUNT(*) FROM product");
        $totalProducts = $stmt->fetchColumn();

        // Total orders
        $stmt = $this->db->query("SELECT COUNT(*) FROM orders");
        $totalOrders = $stmt->fetchColumn();

        // Total users
        $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role='user'");
        $totalUsers = $stmt->fetchColumn();

        // Recent orders
        $stmt = $this->db->query("
            SELECT o.*, u.username, u.full_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC LIMIT 5
        ");
        $recentOrders = $stmt->fetchAll(PDO::FETCH_OBJ);

        ob_start();
        include 'app/views/admin/index.php';
        $content = ob_get_clean();
        
        include 'app/views/admin/layout.php';
    }

    public function orders() {
        $activeMenu = 'orders';
        $pageTitle = 'Quản lý Đặt Lịch - Admin';

        $stmt = $this->db->query("
            SELECT o.*, u.username, u.full_name, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ);

        ob_start();
        include 'app/views/admin/orders.php';
        $content = ob_get_clean();
        
        include 'app/views/admin/layout.php';
    }

    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $status, 'id' => $id]);
            SessionHelper::setFlash('success', 'Đã cập nhật trạng thái đơn!');
        }
        header('Location: ' . BASE_URL . '/Admin/orders');
        exit;
    }

    public function users() {
        $activeMenu = 'users';
        $pageTitle = 'Khách Hàng - Admin';

        $stmt = $this->db->query("SELECT * FROM users WHERE role='user' ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        ob_start();
        include 'app/views/admin/users.php';
        $content = ob_get_clean();
        
        include 'app/views/admin/layout.php';
    }
}
