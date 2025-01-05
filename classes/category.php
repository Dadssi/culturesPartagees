<?php

class Category {
    private $id_category;
    private $label;

    public function __construct($label = null) {
        $this->label = $label;
    }

    // Getters
    public function getId() { return $this->id_category; }
    public function getLabel() { return $this->label; }

    // Setters
    public function setLabel($label) { $this->label = $label; }

    // CRUD Methods
    public function createCategory() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO categories (label) VALUES (?)");
        $stmt->execute([$this->label]);
    }

    public static function read($id_category = null) {
        $db = Database::getInstance()->getConnection();
        if ($id_category) {
            $stmt = $db->prepare("SELECT * FROM categories WHERE id_category = :id_category");
            $stmt->execute(['id_category' => $id_category]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $stmt = $db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE categories SET label = :label WHERE id_category = :id_category");
        return $stmt->execute([
            'label' => $this->label,
            'id_category' => $this->id_category
        ]);
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM categories WHERE id_category = :id_category");
        return $stmt->execute(['id_category' => $this->id_category]);
    }

    // Méthode utilitaire
    public static function getAllCategories() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTotalCategories() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) as total FROM categories");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>