<?php

class Tag {
    private $id_tag;
    private $tag_title;

    public function __construct($tag_title = null) {
        $this->tag_title = $tag_title;
    }

    // Getters
    public function getId() { return $this->id_tag; }
    public function getLabel() { return $this->tag_title; }

    // Setters
    public function setLabel($tag_title) { $this->tag_title = $tag_title; }
    // CRUD Methods
    public function createTag() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO tags (tag_title) VALUES (?)");
        $stmt->execute([$this->tag_title]);
    }

    public static function read($id_tag = null) {
        $db = Database::getInstance()->getConnection();
        if ($id_tag) {
            $stmt = $db->prepare("SELECT * FROM tags WHERE id_tag = :id_tag");
            $stmt->execute(['id_tag' => $id_tag]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $stmt = $db->query("SELECT * FROM tags");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE tags SET tag_title = :tag_title WHERE id_tag = :id_tag");
        return $stmt->execute([
            'tag_title' => $this->tag_title,
            'id_tag' => $this->id_tag
        ]);
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM tags WHERE id_tag = :id_tag");
        return $stmt->execute(['id_tag' => $this->id_tag]);
    }

    // Méthode utilitaire
    public static function getAllTags() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM tags");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTotalCategories() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) as total FROM tags");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>