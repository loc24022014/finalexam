<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Serve static files directly (css, js, images, etc.)
if (file_exists(__DIR__ . $path) && !is_dir(__DIR__ . $path)) {
    return false;
}
// Set the url GET parameter for the MVC router
$_GET['url'] = ltrim($path, '/');
require_once __DIR__ . '/index.php';
