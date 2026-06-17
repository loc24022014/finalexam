<?php
// Quick debug for POST /product
define('BASE_URL', '');
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/ProductModel.php';

$db = (new Database())->getConnection();
$pm = new ProductModel($db);

// Test add
$result = $pm->addProduct('Test Car Debug', 'Test description', 500000000, null, 'TestBrand');
echo "addProduct result: ";
var_dump($result);

if ($result === true) {
    // Lấy ID vừa tạo
    $id = $db->lastInsertId();
    echo "New ID: $id\n";
    
    // Lấy sản phẩm vừa tạo
    $p = $pm->getProductById($id);
    echo "Product: " . json_encode($p) . "\n";
    
    // Xóa
    $pm->deleteProduct($id);
    echo "Deleted OK\n";
}
