<?php
require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/SessionHelper.php';

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->db            = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        SessionHelper::start();
    }

    public function index() {
        $this->list();
    }

    public function list() {
        SessionHelper::requireAdmin();
        $categories = $this->categoryModel->getCategories();
        $success    = SessionHelper::getFlash('success');
        include 'app/views/category/list.php';
    }

    public function add() {
        SessionHelper::requireAdmin();
        $errors = [];
        include 'app/views/category/add.php';
    }

    public function save() {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Category/list');
            exit;
        }
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $slug        = trim($_POST['slug'] ?? '');

        $result = $this->categoryModel->addCategory($name, $description, $slug);
        if ($result === true) {
            SessionHelper::setFlash('success', 'Thêm danh mục thành công!');
            header('Location: ' . BASE_URL . '/Category/list');
            exit;
        } else {
            $errors = $result;
            include 'app/views/category/add.php';
        }
    }

    public function edit($id) {
        SessionHelper::requireAdmin();
        $category = $this->categoryModel->getCategoryById($id);
        $errors   = [];
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            header('Location: ' . BASE_URL . '/Category/list');
        }
    }

    public function update() {
        SessionHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Category/list');
            exit;
        }
        $id          = $_POST['id'];
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $slug        = trim($_POST['slug'] ?? '');

        $result = $this->categoryModel->updateCategory($id, $name, $description, $slug);
        if ($result === true) {
            SessionHelper::setFlash('success', 'Cập nhật danh mục thành công!');
            header('Location: ' . BASE_URL . '/Category/list');
            exit;
        } else {
            $category = $this->categoryModel->getCategoryById($id);
            $errors   = $result;
            include 'app/views/category/edit.php';
        }
    }

    public function confirmDelete($id) {
        SessionHelper::requireAdmin();
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/delete.php';
        } else {
            header('Location: ' . BASE_URL . '/Category/list');
        }
    }

    public function delete($id) {
        SessionHelper::requireAdmin();
        $this->categoryModel->deleteCategory($id);
        SessionHelper::setFlash('success', 'Đã xoá danh mục thành công!');
        header('Location: ' . BASE_URL . '/Category/list');
        exit;
    }
}