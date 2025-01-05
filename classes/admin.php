<?php

class Admin extends User {
    public function __construct($id = null, $nom = null, $email = null, $password = null) {
        parent::__construct($id, $nom, $email, $password);
        $this->role = 'admin';
    }

    public function gererUtilisateurs() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM users WHERE role != 'admin'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function gererCategories() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approuverArticle($articleId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE articles SET status = 'accepted' WHERE id_article = :id");
        return $stmt->execute(['id' => $articleId]);
    }

    public function refuserArticle($articleId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE articles SET status = 'refused' WHERE id_article = :id");
        return $stmt->execute(['id' => $articleId]);
    }
}

?>