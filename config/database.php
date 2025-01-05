<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "culture_partagee";

    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->database", 
                $this->username, 
                $this->password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            die();
        }
    }

    // Empêcher le clonage de l'instance
    private function __clone() {}

    // Méthode pour obtenir l'instance de la connexion
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Méthode pour obtenir la connexion
    public function getConnection() {
        return $this->conn;
    }
}