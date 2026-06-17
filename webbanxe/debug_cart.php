<?php
// Debug: test CartApi trực tiếp
define('BASE_URL', '');
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/ProductModel.php';

$db = (new Database())->getConnection();
$pm = new ProductModel($db);

// Test lấy sản phẩm ID 1
$p = $pm->getProductById(1);
echo "Product ID 1: ";
echo $p ? json_encode($p) : "NULL";
echo "\n";

// Test tạo bảng cart_items
try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS cart_items (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            user_id    INT NOT NULL,
            product_id INT NOT NULL,
            added_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_user_product (user_id, product_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "cart_items table: OK\n";
} catch (Exception $e) {
    echo "cart_items ERROR: " . $e->getMessage() . "\n";
}

// Test insert
$stmt = $db->prepare("INSERT IGNORE INTO cart_items (user_id, product_id) VALUES (1, 1)");
$stmt->execute();
echo "Insert cart: rowCount=" . $stmt->rowCount() . "\n";

// Test select
$stmt2 = $db->prepare("SELECT ci.product_id, p.name, p.price, p.brand FROM cart_items ci JOIN product p ON p.id = ci.product_id WHERE ci.user_id = 1");
$stmt2->execute();
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);
echo "Cart items: " . json_encode($items) . "\n";
