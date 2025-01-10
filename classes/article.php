<?php
class Article {
    private $id;
    private $title;
    private $content;
    private $date_publication;
    private $status;
    private $id_category;
    private $id_author;
    private $image;

    public function __construct($id = null, $title = null, $content = null, $category = null, $author = null, $image = null) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->status = 'pending';
        $this->id_category = $category;
        $this->id_author = $author;
        $this->image = $image;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getContent() { return $this->content; }
    public function getDatePublication() { return $this->date_publication; }
    public function getStatus() { return $this->status; }
    public function getCategory() { return $this->id_category; }
    public function getAuthor() { return $this->id_author; }
    public function getImage() { return $this->image; }

    // Setters
    public function setTitle($title) { $this->title = $title; }
    public function setContent($content) { $this->content = $content; }
    public function setStatus($status) { $this->status = $status; }
    public function setCategory($category) { $this->id_category = $category; }
    public function setImage($image) { $this->image = $image; }

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

    public static function getAllArticles() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM articles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createArticle($title, $content, $category, $author, $image = null) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO articles (title, content, id_category, id_author, article_picture)
            VALUES (:title, :content, :id_category, :id_author, :article_picture)
        ");
        
        // Exécution de la requête avec les paramètres fournis
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'id_category' => $category,
            'id_author' => $author,
            'article_picture' => $image
        ]);
        
        // Retourne l'ID de l'article nouvellement créé
        return $db->lastInsertId();
    }
    
}

$tt = $art->createArticle();
?>