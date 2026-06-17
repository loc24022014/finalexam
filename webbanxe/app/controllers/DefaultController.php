<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';

class DefaultController {
    private $productModel;
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel  = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index() {
        $featuredProducts = $this->productModel->getFeaturedProducts(6);
        $categories       = $this->categoryModel->getCategories();
        include 'app/views/home/index.php';
    }
}
