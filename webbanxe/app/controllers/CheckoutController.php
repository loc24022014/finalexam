<?php
// app/controllers/CheckoutController.php
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/SessionHelper.php';

class CheckoutController {
    private $orderModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
        SessionHelper::start();
        SessionHelper::requireLogin();
    }

    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: ' . BASE_URL . '/Cart');
            exit;
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'];
        }
        $deposit = $total * 0.05; // Cọc 5%
        
        // Mặc định ngày hẹn là ngày mai
        $defaultDate = date('Y-m-d', strtotime('+1 day'));
        
        include 'app/views/checkout/index.php';
    }

    public function process() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: ' . BASE_URL . '/Cart');
            exit;
        }

        $payment_method = $_POST['payment_method'] ?? 'cash';
        $appointment_date = $_POST['appointment_date'] ?? date('Y-m-d', strtotime('+1 day'));
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'];
        }
        $deposit = $total * 0.05;

        $user_id = SessionHelper::get('user_id');
        
        // Tạo order (total_amount ở đây là tiền cọc)
        $order_id = $this->orderModel->createOrder($user_id, $deposit, $payment_method, $appointment_date);
        
        if ($order_id) {
            // Thêm order items
            foreach ($cart as $item) {
                $this->orderModel->createOrderItem($order_id, $item['id'], $item['price']);
            }
            
            // Chuyển hướng thanh toán giả lập
            if ($payment_method == 'cash') {
                // Tiền mặt thì hoàn tất luôn nên xoá giỏ hàng
                unset($_SESSION['cart']);
                SessionHelper::setFlash('success', 'Đặt lịch thành công! Vui lòng đến showroom đúng hẹn để đặt cọc.');
                header('Location: ' . BASE_URL . '/Checkout/success/' . $order_id);
            } else {
                header('Location: ' . BASE_URL . '/Checkout/mock_payment/' . $order_id . '?method=' . $payment_method);
            }
            exit;
        } else {
            SessionHelper::setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại.');
            header('Location: ' . BASE_URL . '/Checkout');
            exit;
        }
    }
    
    public function mock_payment($order_id) {
        $method = $_GET['method'] ?? 'qr';
        $order = $this->orderModel->getOrderById($order_id);
        if (!$order) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        include 'app/views/checkout/mock_payment.php';
    }
    
    public function complete_payment($order_id) {
        $this->orderModel->updatePaymentStatus($order_id, 'completed');
        unset($_SESSION['cart']); // Đã thanh toán thành công thì xoá giỏ hàng
        SessionHelper::setFlash('success', 'Thanh toán thành công! Đã xác nhận cọc giữ xe.');
        header('Location: ' . BASE_URL . '/Checkout/success/' . $order_id);
    }
    
    public function success($order_id) {
        $order = $this->orderModel->getOrderById($order_id);
        include 'app/views/checkout/success.php';
    }
}
