<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/SessionHelper.php';

class SearchController {
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
        $keyword    = trim($_GET['keyword'] ?? $_GET['q'] ?? '');
        $minPrice   = $_GET['min_price'] ?? '';
        $maxPrice   = $_GET['max_price'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $brand      = $_GET['brand'] ?? '';

        $products   = $this->productModel->searchProducts($keyword, $minPrice, $maxPrice, $categoryId, $brand);
        $categories = $this->categoryModel->getCategories();
        $brands     = $this->productModel->getBrands();

        include 'app/views/product/search.php';
    }
}
