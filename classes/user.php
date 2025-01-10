<?php
abstract class User {
    protected $id;
    protected $firstName;
    protected $lastName;
    protected $nom;
    protected $email;
    protected $password;
    protected $role;

    public function __construct($id = null, $firstName = null, $lastName = null, $nom = null, $email = null, $password = null) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->nom = $nom;
        $this->email = $email;
        $this->password = $password;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getFisrtName() { return $this->firstName;}
    public function getLastName() { return $this->lastName;}
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setFirstName($firstName) { $this->firstName = $firstName;}
    public function setLastName($lastName) { $this->lastName = $lastName;}
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { 
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Méthodes communes
    public function login($email, $password) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND role = :role");
        $stmt->execute(['email' => $email, 'role' => $this->role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function logout() {
        session_destroy();
    }

    public function updateProfile($data) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET nom = :nom, first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id");
        return $stmt->execute([
            'first_name' => $data['first_name'], 
            'last_name' => $data['last_name'], 
            'nom' => $data['nom'],
            'email' => $data['email'],
            'id' => $this->id
        ]);
    }

    public static function getInfoUser($id) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE id_user = :id_user");
            $stmt->execute(['id_user' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des informations de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
 
    public static function getAllUsers() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT 
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.role,
                    u.statut,
                    COUNT(a.id_article) AS article_count,
                    u.registration_date
                FROM users u
                LEFT JOIN articles a ON u.id_user = a.id_author
                GROUP BY u.id_user, u.first_name, u.last_name, u.email, u.role, u.statut, u.registration_date;
                ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des informations des utilisateurs : " . $e->getMessage();
            return false;
        }
    }

    public static function countUsersByRole($role) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM users WHERE role = :role");
            $stmt->execute(['role' => $role]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            echo "Erreur lors du comptage des utilisateurs pour le rôle '$role' : " . $e->getMessage();
            return 0;
        }
    } 
}
?>