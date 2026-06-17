<?php
// app/models/OrderModel.php
class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ── Tạo đơn hàng mới ─────────────────────────────────────
    public function createOrder($user_id, $total_amount, $payment_method, $appointment_date) {
        $stmt = $this->conn->prepare(
            "INSERT INTO orders (user_id, total_amount, payment_method, appointment_date)
             VALUES (:user_id, :total_amount, :payment_method, :appointment_date)"
        );
        $stmt->bindParam(':user_id',          $user_id);
        $stmt->bindParam(':total_amount',      $total_amount);
        $stmt->bindParam(':payment_method',    $payment_method);
        $stmt->bindParam(':appointment_date',  $appointment_date);
        return $stmt->execute() ? $this->conn->lastInsertId() : false;
    }

    // ── Thêm sản phẩm vào đơn hàng ───────────────────────────
    public function createOrderItem($order_id, $product_id, $price) {
        $stmt = $this->conn->prepare(
            "INSERT INTO order_items (order_id, product_id, price)
             VALUES (:order_id, :product_id, :price)"
        );
        $stmt->bindParam(':order_id',   $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':price',      $price);
        return $stmt->execute();
    }

    // ── Cập nhật trạng thái thanh toán ───────────────────────
    public function updatePaymentStatus($order_id, $status) {
        $allowed = ['pending', 'completed', 'failed'];
        if (!in_array($status, $allowed)) return false;
        $stmt = $this->conn->prepare(
            "UPDATE orders SET payment_status = :status WHERE id = :id"
        );
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id',     $order_id);
        return $stmt->execute();
    }

    // ── Cập nhật trạng thái đơn hàng (admin) ─────────────────
    public function updateOrderStatus($order_id, $status) {
        $allowed = ['pending', 'confirmed', 'cancelled'];
        if (!in_array($status, $allowed)) return false;
        $stmt = $this->conn->prepare(
            "UPDATE orders SET status = :status WHERE id = :id"
        );
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id',     $order_id);
        return $stmt->execute();
    }

    // ── Lấy đơn hàng theo ID (kèm sản phẩm) ─────────────────
    public function getOrderById($order_id) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, u.username, u.email
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             WHERE o.id = :id"
        );
        $stmt->bindParam(':id', $order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // ── Lấy danh sách items của đơn hàng ─────────────────────
    public function getOrderItems($order_id) {
        $stmt = $this->conn->prepare(
            "SELECT oi.*, p.name AS product_name, p.brand
             FROM order_items oi
             LEFT JOIN product p ON p.id = oi.product_id
             WHERE oi.order_id = :order_id"
        );
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ── Lấy tất cả đơn hàng của 1 user ───────────────────────
    public function getOrdersByUser($user_id) {
        $stmt = $this->conn->prepare(
            "SELECT o.*,
                    COUNT(oi.id) AS item_count
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             WHERE o.user_id = :user_id
             GROUP BY o.id
             ORDER BY o.created_at DESC"
        );
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ── Lấy tất cả đơn hàng (admin) ──────────────────────────
    public function getAllOrders($limit = 50, $offset = 0) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, u.username, u.email,
                    COUNT(oi.id) AS item_count
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             LEFT JOIN order_items oi ON oi.order_id = o.id
             GROUP BY o.id
             ORDER BY o.created_at DESC
             LIMIT :lim OFFSET :off"
        );
        $stmt->bindValue(':lim', (int)$limit,  PDO::PARAM_INT);
        $stmt->bindValue(':off', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ── Xóa đơn hàng (admin) ─────────────────────────────────
    public function deleteOrder($order_id) {
        $stmt = $this->conn->prepare("DELETE FROM orders WHERE id = :id");
        $stmt->bindParam(':id', $order_id);
        return $stmt->execute();
    }

    // ── Tổng số đơn hàng ─────────────────────────────────────
    public function countAll() {
        return $this->conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    }
}
