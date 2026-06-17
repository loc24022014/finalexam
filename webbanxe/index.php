<?php
// ── CORS Headers – cho phép frontend riêng gọi API ──────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
// Trả về 200 ngay cho preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = str_replace('/index.php', '', $scriptName);
define('BASE_URL', $basePath);

function getImageUrl($image) {
    if (empty($image)) {
        return 'https://via.placeholder.com/800x500?text=No+Image';
    }
    if (filter_var($image, FILTER_VALIDATE_URL)) {
        return $image;
    }
    return BASE_URL . '/public/uploads/products/' . $image;
}

require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';

try {
    $db_init = (new Database())->getConnection();
    if ($db_init) {
        $catModelInit = new CategoryModel($db_init);
        $GLOBALS['categories_nav'] = $catModelInit->getCategories();
    }
} catch (Exception $e) {
    $GLOBALS['categories_nav'] = [];
}
require_once 'app/models/ProductModel.php';
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// ════════════════════════════════════════════════════════
//  API ROUTER – /api/{resource}/{id?}
//  Dùng class từ app/api/ (AuthApi, ProductApi, CategoryApi)
// ════════════════════════════════════════════════════════
if (isset($url[0]) && strtolower($url[0]) === 'api') {
    header('Content-Type: application/json; charset=utf-8');

    $resource = strtolower($url[1] ?? '');
    $action   = strtolower($url[2] ?? '');          // dùng cho /api/auth/{action}
    $id       = ($url[2] ?? '') !== '' ? (int)$url[2] : null;
    $method   = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    // ── /api/auth/* ─────────────────────────────────────
    if ($resource === 'auth') {
        require_once 'app/api/AuthApi.php';
        $api = new AuthApi();
        match ($action) {
            'login' => $api->login(),
            'me'    => $api->me(),
            default => (function () {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Endpoint không tồn tại. Dùng /api/auth/login hoặc /api/auth/me']);
                exit;
            })()
        };
        exit;
    }

    // ── /api/product/* ──────────────────────────────────
    if ($resource === 'product') {
        require_once 'app/api/ProductApi.php';
        $api = new ProductApi();
        if ($method === 'GET')         { $id ? $api->detail($id) : $api->list(); }
        elseif ($method === 'POST')    { $api->create(); }
        elseif ($method === 'PUT')     { $id ? $api->update($id) : (http_response_code(400) ?: print json_encode(['success'=>false,'message'=>'Thieu ID'])); }
        elseif ($method === 'DELETE')  { $id ? $api->delete($id) : (http_response_code(400) ?: print json_encode(['success'=>false,'message'=>'Thieu ID'])); }
        else { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Method not allowed']); }
        exit;
    }

    // ── /api/category/* ─────────────────────────────────
    if ($resource === 'category') {
        require_once 'app/api/CategoryApi.php';
        $api = new CategoryApi();
        if ($method === 'GET')         { $id ? $api->detail($id) : $api->list(); }
        elseif ($method === 'POST')    { $api->create(); }
        elseif ($method === 'PUT')     { $id ? $api->update($id) : (http_response_code(400) ?: print json_encode(['success'=>false,'message'=>'Thieu ID'])); }
        elseif ($method === 'DELETE')  { $id ? $api->delete($id) : (http_response_code(400) ?: print json_encode(['success'=>false,'message'=>'Thieu ID'])); }
        else { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Method not allowed']); }
        exit;
    }

    // ── /api/cart/* ─────────────────────────────────────
    if ($resource === 'cart') {
        require_once 'app/api/CartApi.php';
        $api   = new CartApi();
        $subact = strtolower($url[2] ?? '');   // "add" hoặc ID
        if ($method === 'GET')                      { $api->list(); }
        elseif ($method === 'POST')                 { $api->add(); }  // POST /api/cart  thêm vào giỏ
        elseif ($method === 'DELETE' && $id)        { $api->remove($id); }
        elseif ($method === 'DELETE' && !$id)       { $api->clear(); }
        else { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Method not allowed']); }
        exit;
    }

    // ── /api/order/* ────────────────────────────────────
    if ($resource === 'order') {
        require_once 'app/api/OrderApi.php';
        $api    = new OrderApi();
        // url[2] có thể là: số ID, "all", "checkout"
        // url[3] có thể là: "pay", "status", "force"
        $seg2   = strtolower($url[2] ?? '');
        $seg3   = strtolower($url[3] ?? '');
        $oid    = is_numeric($seg2) ? (int)$seg2 : null;

        if ($method === 'GET'    && $seg2 === 'all')       { $api->allOrders(); }
        elseif ($method === 'GET'  && $oid && !$seg3)      { $api->detail($oid); }
        elseif ($method === 'GET'  && !$seg2)              { $api->myOrders(); }
        elseif ($method === 'POST' && $seg2 === 'checkout'){ $api->checkout(); }
        elseif ($method === 'POST' && $oid && $seg3==='pay'){$api->pay($oid); }
        elseif ($method === 'PUT'  && $oid && $seg3==='status'){$api->updateStatus($oid);}
        elseif ($method === 'DELETE' && $oid && $seg3==='force'){$api->forceDelete($oid);}
        elseif ($method === 'DELETE' && $oid)              { $api->cancel($oid); }
        else { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Endpoint hoặc method không đúng']); }
        exit;
    }

    // ── Endpoint không tồn tại ──────────────────────────
    http_response_code(404);
    echo json_encode([
        'success'   => false,
        'message'   => "API '$resource' không tồn tại",
        'endpoints' => [
            'POST   /api/auth/login',
            'GET    /api/auth/me',
            '---CART---',
            'GET    /api/cart',
            'POST   /api/cart',
            'DELETE /api/cart/{product_id}',
            'DELETE /api/cart',
            '---ORDER---',
            'POST   /api/order/checkout',
            'GET    /api/order',
            'GET    /api/order/{id}',
            'POST   /api/order/{id}/pay',
            'DELETE /api/order/{id}',
            'GET    /api/order/all  [admin]',
            'PUT    /api/order/{id}/status  [admin]',
            'DELETE /api/order/{id}/force   [admin]',
            'GET    /api/product',
            'GET    /api/product/{id}',
            'POST   /api/product',
            'PUT    /api/product/{id}',
            'DELETE /api/product/{id}',
            'GET    /api/category',
            'GET    /api/category/{id}',
            'POST   /api/category',
            'PUT    /api/category/{id}',
            'DELETE /api/category/{id}',
        ]
    ]);
    exit;
}

// Kiểm tra phần đầu tiên của URL để xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';
// Kiểm tra phần thứ hai của URL để xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Kiểm tra xem controller và action có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    // Xử lý không tìm thấy controller
    die('Controller not found');
}
require_once 'app/controllers/' . $controllerName . '.php';
$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    // Xử lý không tìm thấy action
    die('Action not found');
}
// Gọi action với các tham số còn lại (nếu có)
call_user_func_array([$controller, $action], array_slice($url, 2));