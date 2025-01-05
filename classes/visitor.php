<?php

class Visitor extends User {
    public function __construct($id = null, $nom = null, $email = null, $password = null) {
        parent::__construct($id, $nom, $email, $password);
        $this->role = 'visitor';
    }

    public function register($nom, $email, $password) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :password, :role)");
        return $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'visitor'
        ]);
    }

    public function consulterArticles() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM articles WHERE status = 'accepted'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrerParCategorie($categoryId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM articles WHERE status = 'accepted' AND id_category = :category");
        $stmt->execute(['category' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>