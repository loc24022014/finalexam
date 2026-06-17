<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/UploadHelper.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->db            = (new Database())->getConnection();
        $this->productModel  = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        SessionHelper::start();
    }

    public function index() {
        SessionHelper::requireAdmin();
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id) {
        $product = $this->productModel->getProductById($id);
        $categories = $this->categoryModel->getCategories();
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            header('Location: ' . BASE_URL . '/Product');
        }
    }

    public function add() {
        SessionHelper::requireAdmin();
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        include 'app/views/product/add.php';
    }

    public function save() {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Product');
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = $_POST['price'] ?? '';
        $category_id = $_POST['category_id'] ?? null;
        $brand       = trim($_POST['brand'] ?? '');

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $brand);
        if ($result === true) {
            SessionHelper::setFlash('success', 'Thêm xe thành công!');
            header('Location: ' . BASE_URL . '/Product');
            exit;
        } else {
            $errors     = $result;
            $categories = $this->categoryModel->getCategories();
            include 'app/views/product/add.php';
        }
    }

    public function edit($id) {
        SessionHelper::requireAdmin();
        $product    = $this->productModel->getProductById($id);
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            header('Location: ' . BASE_URL . '/Product');
        }
    }

    public function update() {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Product');
            exit;
        }

        $id          = $_POST['id'];
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = $_POST['price'] ?? '';
        $category_id = $_POST['category_id'] ?? null;
        $brand       = trim($_POST['brand'] ?? '');

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $brand);
        if ($result) {
            SessionHelper::setFlash('success', 'Cập nhật xe thành công!');
            header('Location: ' . BASE_URL . '/Product');
            exit;
        } else {
            $product    = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getCategories();
            $errors = ['general' => 'Đã có lỗi xảy ra khi lưu.'];
            include 'app/views/product/edit.php';
        }
    }

    public function confirmDelete($id) {
        SessionHelper::requireAdmin();
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/delete.php';
        } else {
            header('Location: ' . BASE_URL . '/Product');
        }
    }

    public function delete($id) {
        SessionHelper::requireAdmin();
        $this->productModel->deleteProduct($id);
        SessionHelper::setFlash('success', 'Đã xoá xe thành công!');
        header('Location: ' . BASE_URL . '/Product');
        exit;
    }
}