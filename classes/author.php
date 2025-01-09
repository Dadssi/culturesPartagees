<?php
    class Author extends User {
    public function __construct($id = null, $nom = null, $email = null, $password = null) {
        parent::__construct($id, $nom, $email, $password);
        $this->role = 'author';
    }

    public function register($nom, $email, $password) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :password, :role)");
        return $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'author'
        ]);
    }

    public function createArticle($title, $content, $categoryId, $image = null) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO articles (title, content, id_category, id_author, image_path) VALUES (:title, :content, :category, :author, :image)");
        return $stmt->execute([
            'title' => $title,
            'content' => $content,
            'category' => $categoryId,
            'author' => $this->id,
            'image' => $image
        ]);
    }

    public function modifierArticle($articleId, $data) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE articles SET title = :title, content = :content, id_category = :category, image_path = :image WHERE id_article = :id AND id_author = :author");
        return $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'category' => $data['category'],
            'image' => $data['image'],
            'id' => $articleId,
            'author' => $this->id
        ]);
    }

    public function supprimerArticle($articleId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM articles WHERE id_article = :id AND id_author = :author");
        return $stmt->execute(['id' => $articleId, 'author' => $this->id]);
    }

    public function consulterMesArticles() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM articles WHERE id_author = :author");
        $stmt->execute(['author' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>