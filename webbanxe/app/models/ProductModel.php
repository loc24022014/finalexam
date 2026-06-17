<?php
// app/models/ProductModel.php
class ProductModel {
    private $conn;
    private $table_name = "product";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getProducts() {
        $query = "SELECT p.id, p.name, p.description, p.price, p.brand,
                         c.name as category_name
                  FROM {$this->table_name} p
                  LEFT JOIN category c ON p.category_id = c.id
                  ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getFeaturedProducts($limit = 6) {
        $query = "SELECT p.id, p.name, p.description, p.price, p.brand,
                         c.name as category_name
                  FROM {$this->table_name} p
                  LEFT JOIN category c ON p.category_id = c.id
                  ORDER BY p.id DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductById($id) {
        $query = "SELECT p.*, c.name as category_name
                  FROM {$this->table_name} p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function searchProducts($keyword = '', $minPrice = null, $maxPrice = null, $categoryId = null, $brand = null) {
        $conditions = ['1=1'];
        $params = [];

        if (!empty($keyword)) {
            $conditions[] = "(p.name LIKE :keyword OR p.description LIKE :keyword OR p.brand LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        if ($minPrice !== null && $minPrice !== '') {
            $conditions[] = "p.price >= :min_price";
            $params[':min_price'] = (int)$minPrice;
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $conditions[] = "p.price <= :max_price";
            $params[':max_price'] = (int)$maxPrice;
        }
        if (!empty($categoryId)) {
            $conditions[] = "p.category_id = :category_id";
            $params[':category_id'] = (int)$categoryId;
        }
        if (!empty($brand)) {
            $conditions[] = "p.brand LIKE :brand";
            $params[':brand'] = '%' . $brand . '%';
        }

        $where = implode(' AND ', $conditions);
        $query = "SELECT p.id, p.name, p.description, p.price, p.brand,
                         c.name as category_name
                  FROM {$this->table_name} p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE {$where}
                  ORDER BY p.price ASC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getBrands() {
        $stmt = $this->conn->prepare("SELECT DISTINCT brand FROM {$this->table_name} WHERE brand IS NOT NULL AND brand != '' ORDER BY brand");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function addProduct($name, $description, $price, $category_id, $brand = null) {
        $errors = [];
        if (empty($name))        $errors['name'] = 'Tên sản phẩm không được để trống';
        if (empty($description)) $errors['description'] = 'Mô tả không được để trống';
        if (!is_numeric($price) || $price < 0) $errors['price'] = 'Giá sản phẩm không hợp lệ';
        if (count($errors) > 0)  return $errors;

        $query = "INSERT INTO {$this->table_name} (name, description, price, category_id, brand)
                  VALUES (:name, :description, :price, :category_id, :brand)";
        $stmt = $this->conn->prepare($query);
        $name        = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price       = (int)$price;
        $stmt->bindParam(':name',        $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price',       $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':brand',       $brand);
        if ($stmt->execute()) return true;
        return false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $brand = null) {
        $query = "UPDATE {$this->table_name}
                  SET name=:name, description=:description, price=:price,
                      category_id=:category_id, brand=:brand
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $name        = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price       = (int)$price;
        $stmt->bindParam(':id',          $id);
        $stmt->bindParam(':name',        $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price',       $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':brand',       $brand);
        if ($stmt->execute()) return true;
        return false;
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM {$this->table_name} WHERE id=:id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}