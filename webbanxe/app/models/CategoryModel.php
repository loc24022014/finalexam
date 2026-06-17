<?php
// app/models/CategoryModel.php
class CategoryModel {
    private $conn;
    private $table_name = "category";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategories() {
        $query = "SELECT c.id, c.name, c.description, c.slug,
                         COUNT(p.id) as product_count
                  FROM {$this->table_name} c
                  LEFT JOIN product p ON p.category_id = c.id
                  GROUP BY c.id
                  ORDER BY c.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addCategory($name, $description = '', $slug = '') {
        $errors = [];
        if (empty($name)) $errors['name'] = 'Tên danh mục không được để trống.';
        if (!empty($errors)) return $errors;

        if (empty($slug)) $slug = $this->makeSlug($name);

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table_name} (name, description, slug) VALUES (:name, :description, :slug)"
        );
        $stmt->bindParam(':name',        $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':slug',        $slug);
        if ($stmt->execute()) return true;
        return ['general' => 'Đã có lỗi xảy ra.'];
    }

    public function updateCategory($id, $name, $description = '', $slug = '') {
        $errors = [];
        if (empty($name)) $errors['name'] = 'Tên danh mục không được để trống.';
        if (!empty($errors)) return $errors;

        if (empty($slug)) $slug = $this->makeSlug($name);

        $stmt = $this->conn->prepare(
            "UPDATE {$this->table_name} SET name=:name, description=:description, slug=:slug WHERE id=:id"
        );
        $stmt->bindParam(':id',          $id);
        $stmt->bindParam(':name',        $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':slug',        $slug);
        if ($stmt->execute()) return true;
        return ['general' => 'Đã có lỗi xảy ra.'];
    }

    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id=:id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function makeSlug($text) {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/\s+/', '-', trim($text));
        $text = preg_replace('/[^a-z0-9\-]/', '', $text);
        return $text;
    }
}