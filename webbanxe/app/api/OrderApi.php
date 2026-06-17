<?php
/**
 * app/api/OrderApi.php
 * ──────────────────────────────────────────────────────────────
 * Quản lý đơn hàng & thanh toán – phản chiếu CheckoutController.
 *
 * ── Người dùng (cần token) ────────────────────────────────────
 * POST   /api/order/checkout          → đặt lịch từ giỏ hàng    🔒
 * GET    /api/order                   → đơn hàng của tôi         🔒
 * GET    /api/order/{id}              → chi tiết 1 đơn            🔒
 * POST   /api/order/{id}/pay         → mô phỏng thanh toán       🔒
 * DELETE /api/order/{id}             → hủy đơn (chỉ pending)     🔒
 *
 * ── Admin (cần token + role admin) ───────────────────────────
 * GET    /api/order/all              → toàn bộ đơn hàng          🔴
 * PUT    /api/order/{id}/status      → cập nhật trạng thái        🔴
 * DELETE /api/order/{id}/force       → xóa đơn bất kỳ            🔴
 */

require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/JwtHelper.php';
require_once 'app/api/CartApi.php';  // dùng CartApi để lấy giỏ từ DB

class OrderApi {

    private OrderModel   $orders;
    private ProductModel $products;
    private CartApi      $cartApi;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        $db             = (new Database())->getConnection();
        $this->orders   = new OrderModel($db);
        $this->products = new ProductModel($db);
        $this->cartApi  = new CartApi();  // dùng lấy giỏ hàng từ DB
    }

    // ══════════════════════════════════════════════════════════
    //  POST /api/order/checkout
    // ══════════════════════════════════════════════════════════
    public function checkout(): void {
        $auth = JwtHelper::requireAuth();

        // Lấy giỏ hàng từ DB (thay vì session)
        $cart = $this->cartApi->getCartForCheckout($auth['user_id']);
        if (empty($cart)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Gio hang trong. Them san pham truoc khi dat lich.']);
            exit;
        }

        $d = json_decode(file_get_contents('php://input'), true) ?: [];
        $payment_method   = $d['payment_method']   ?? 'cash';
        $appointment_date = $d['appointment_date']  ?? date('Y-m-d', strtotime('+1 day'));

        $allowed_methods = ['cash', 'qr', 'momo', 'bank', 'shopeepay'];
        if (!in_array($payment_method, $allowed_methods)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Phương thức thanh toán không hợp lệ', 'allowed' => $allowed_methods]);
            exit;
        }

        if (strtotime($appointment_date) < strtotime('today')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Ngày hẹn phải từ hôm nay trở đi']);
            exit;
        }

        // Tính tổng và tiền cọc 5%
        $total   = array_sum(array_column($cart, 'price'));
        $deposit = round($total * 0.05);

        // Tạo order
        $order_id = $this->orders->createOrder(
            $auth['user_id'],
            $deposit,            // total_amount lưu tiền cọc
            $payment_method,
            $appointment_date
        );

        if (!$order_id) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Không thể tạo đơn hàng, vui lòng thử lại']);
            exit;
        }

        // Thêm order items (cart có field product_id)
        foreach ($cart as $item) {
            $pid = $item['product_id'] ?? $item['id'] ?? 0;
            $this->orders->createOrderItem($order_id, $pid, $item['price']);
        }

        // Với thanh toán tiền mặt → hoàn tất luôn, xóa giỏ
        if ($payment_method === 'cash') {
            $this->orders->updatePaymentStatus($order_id, 'completed');
            $this->cartApi->clearByUser($auth['user_id']); // xoa gio hang DB
            echo json_encode([
                'success'          => true,
                'message'          => 'Đặt lịch thành công! Vui lòng đến showroom đúng hẹn để đặt cọc.',
                'order_id'         => (int)$order_id,
                'payment_method'   => $payment_method,
                'payment_status'   => 'completed',
                'appointment_date' => $appointment_date,
                'deposit_amount'   => $deposit,
                'total_vehicle'    => $total,
                'items_count'      => count($cart),
            ]);
        } else {
            // Online payment → trả về link giả lập
            echo json_encode([
                'success'          => true,
                'message'          => 'Đơn hàng đã tạo. Vui lòng hoàn tất thanh toán.',
                'order_id'         => (int)$order_id,
                'payment_method'   => $payment_method,
                'payment_status'   => 'pending',
                'appointment_date' => $appointment_date,
                'deposit_amount'   => $deposit,
                'total_vehicle'    => $total,
                'items_count'      => count($cart),
                'next_step'        => "POST /api/order/{$order_id}/pay  để xác nhận thanh toán",
            ]);
        }
        exit;
    }

    // ══════════════════════════════════════════════════════════
    //  POST /api/order/{id}/pay  – mô phỏng xác nhận thanh toán
    // ══════════════════════════════════════════════════════════
    public function pay(int $order_id): void {
        $auth  = JwtHelper::requireAuth();
        $order = $this->orders->getOrderById($order_id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            exit;
        }

        // Chỉ chủ đơn hàng (hoặc admin) mới được thanh toán
        if ($order->user_id != $auth['user_id'] && $auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này']);
            exit;
        }

        if ($order->payment_status === 'completed') {
            echo json_encode(['success' => false, 'message' => 'Đơn hàng này đã được thanh toán trước đó']);
            exit;
        }

        $this->orders->updatePaymentStatus($order_id, 'completed');
        $this->cartApi->clearByUser($auth['user_id']); // xoa gio hang DB

        echo json_encode([
            'success'   => true,
            'message'   => 'Thanh toán thành công! Đã xác nhận cọc giữ xe.',
            'order_id'  => $order_id,
            'paid_by'   => $auth['username'],
        ]);
        exit;
    }

    // ══════════════════════════════════════════════════════════
    //  GET /api/order  – đơn hàng của tôi
    // ══════════════════════════════════════════════════════════
    public function myOrders(): void {
        $auth   = JwtHelper::requireAuth();
        $orders = $this->orders->getOrdersByUser($auth['user_id']);

        echo json_encode([
            'success' => true,
            'count'   => count($orders),
            'data'    => $orders,
        ]);
        exit;
    }

    // ══════════════════════════════════════════════════════════
    //  GET /api/order/{id}  – chi tiết đơn hàng
    // ══════════════════════════════════════════════════════════
    public function detail(int $order_id): void {
        $auth  = JwtHelper::requireAuth();
        $order = $this->orders->getOrderById($order_id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            exit;
        }

        // Chỉ admin hoặc chủ đơn hàng mới được xem
        if ($order->user_id != $auth['user_id'] && $auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền xem đơn hàng này']);
            exit;
        }

        $items = $this->orders->getOrderItems($order_id);

        echo json_encode([
            'success' => true,
            'data'    => [
                'order' => $order,
                'items' => $items,
            ],
        ]);
        exit;
    }

    // ══════════════════════════════════════════════════════════
    //  DELETE /api/order/{id}  – hủy đơn (chỉ khi còn pending)
    // ══════════════════════════════════════════════════════════
    public function cancel(int $order_id): void {
        $auth  = JwtHelper::requireAuth();
        $order = $this->orders->getOrderById($order_id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            exit;
        }

        if ($order->user_id != $auth['user_id'] && $auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền hủy đơn hàng này']);
            exit;
        }

        if ($order->payment_status === 'completed') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng đã thanh toán']);
            exit;
        }

        $this->orders->updateOrderStatus($order_id, 'cancelled');
        echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng thành công']);
        exit;
    }

    // ══════════════════════════════════════════════════════════
    //  GET /api/order/all  – tất cả đơn hàng (admin only)
    // ══════════════════════════════════════════════════════════
    public function allOrders(): void {
        $auth = JwtHelper::requireAuth();
        if ($auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới xem được toàn bộ đơn hàng']);
            exit;
        }

        $limit  = max(1, min(100, (int)($_GET['limit']  ?? 50)));
        $offset = max(0, (int)($_GET['offset'] ?? 0));
        $orders = $this->orders->getAllOrders($limit, $offset);
        $total  = $this->orders->countAll();

        echo json_encode([
            'success' => true,
            'total'   => (int)$total,
            'limit'   => $limit,
            'offset'  => $offset,
            'data'    => $orders,
        ]);
        exit;
    }

    // ══════════════════════════════════════════════════════════
    //  PUT /api/order/{id}/status  – cập nhật trạng thái (admin)
    //  Body: { "status": "confirmed" | "cancelled" | "pending" }
    // ══════════════════════════════════════════════════════════
    public function updateStatus(int $order_id): void {
        $auth = JwtHelper::requireAuth();
        if ($auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới được cập nhật trạng thái']);
            exit;
        }

        $order = $this->orders->getOrderById($order_id);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            exit;
        }

        $d      = json_decode(file_get_contents('php://input'), true) ?: [];
        $status = $d['status'] ?? '';

        if (!$this->orders->updateOrderStatus($order_id, $status)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ', 'allowed' => ['pending','confirmed','cancelled']]);
            exit;
        }

        echo json_encode([
            'success'    => true,
            'message'    => "Đơn hàng #{$order_id} → trạng thái: {$status}",
            'updated_by' => $auth['username'],
        ]);
        exit;
    }

    // ══════════════════════════════════════════════════════════
    //  DELETE /api/order/{id}/force  – xóa đơn bất kỳ (admin)
    // ══════════════════════════════════════════════════════════
    public function forceDelete(int $order_id): void {
        $auth = JwtHelper::requireAuth();
        if ($auth['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ admin mới được xóa đơn hàng']);
            exit;
        }

        $order = $this->orders->getOrderById($order_id);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            exit;
        }

        $this->orders->deleteOrder($order_id);
        echo json_encode([
            'success'    => true,
            'message'    => "Đã xóa đơn hàng #{$order_id}",
            'deleted_by' => $auth['username'],
        ]);
        exit;
    }
}
