<?php
require 'app/config/database.php';
$db = (new Database())->getConnection();
$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(15,0) NOT NULL DEFAULT 0,
    payment_method VARCHAR(50) DEFAULT 'cash',
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    appointment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);");
$db->exec("CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    price DECIMAL(15,0) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);");
echo 'DB updated';
