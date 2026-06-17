<?php
define('BASE_URL', '');
require_once __DIR__ . '/app/config/database.php';

$db = (new Database())->getConnection();

// Liệt kê các bảng
$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables: " . implode(', ', $tables) . "\n";

// Đếm sản phẩm
foreach ($tables as $t) {
    $cnt = $db->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
    echo "  $t: $cnt rows\n";
}

// Xem cấu trúc bảng product (nếu có)
if (in_array('product', $tables)) {
    $cols = $db->query("DESCRIBE product")->fetchAll(PDO::FETCH_ASSOC);
    echo "\nCols product: " . implode(', ', array_column($cols, 'Field')) . "\n";
    $row = $db->query("SELECT * FROM product LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    echo "Sample: " . json_encode($row) . "\n";
}
