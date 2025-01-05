<?php
class Article {
    private $id;
    private $title;
    private $content;
    private $date_publication;
    private $status;
    private $id_category;
    private $id_author;
    private $image_path;

    public function __construct($id = null, $title = null, $content = null, $category = null, $author = null, $image = null) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->id_category = $category;
        $this->id_author = $author;
        $this->image_path = $image;
        $this->status = 'pending';
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getContent() { return $this->content; }
    public function getDatePublication() { return $this->date_publication; }
    public function getStatus() { return $this->status; }
    public function getCategory() { return $this->id_category; }
    public function getAuthor() { return $this->id_author; }
    public function getImagePath() { return $this->image_path; }

    // Setters
    public function setTitle($title) { $this->title = $title; }
    public function setContent($content) { $this->content = $content; }
    public function setCategory($category) { $this->id_category = $category; }
    public function setImagePath($image) { $this->image_path = $image; }
    public function setStatus($status) { $this->status = $status; }

    // Méthodes statiques
    public static function getByCategory($categoryId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM articles WHERE id_category = :category AND status = 'accepted'");
        $stmt->execute(['category' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByAuthor($authorId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM articles WHERE id_author = :author");
        $stmt->execute(['author' => $authorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>