<?php
require_once 'app/config/database.php';

try {
    $db = (new Database())->getConnection();
    
    $queries = [
        "UPDATE product SET image = 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80' WHERE id = 1",
        "UPDATE product SET image = 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80' WHERE id = 2",
        "UPDATE product SET image = 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80' WHERE id = 3",
        "UPDATE product SET image = 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80' WHERE id = 4",
        "UPDATE product SET image = 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80' WHERE id = 5",
        "UPDATE product SET image = 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80' WHERE id = 6"
    ];

    foreach ($queries as $q) {
        $db->exec($q);
    }
    echo "SUCCESS";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
