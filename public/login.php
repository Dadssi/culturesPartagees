<?php
require_once '../config/database.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        $errors[] = "L'email est obligatoire.";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    }

    if (empty($errors)) {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    header('Location: adminDashboard.php');
                    exit;
                } elseif ($user['role'] === 'author') {
                    header('Location: authorDashboard.php');
                    exit;
                } elseif ($user['role'] === 'visitor') {
                    header('Location: articles.php');
                    exit;
                }
            } else {
                $errors[] = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données : " . $e->getMessage();
        }
    }
}
// --------------------------

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données du formulaire
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $photo = $_FILES['photo'] ?? null;

    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }
    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire.";
    }
    if (empty($email)) {
        $errors[] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    }
    if (empty($role)) {
        $errors[] = "Le rôle est obligatoire.";
    }

    // Gestion du téléchargement de la photo
    $photoPath = null;
    if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/imgs/uploads/';
        $photoName = uniqid() . '-' . basename($photo['name']);
        $photoPath = $uploadDir . $photoName;

        if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
            $errors[] = "Échec du téléchargement de la photo.";
        }
    }

    if (empty($errors)) {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        try {
            // Vérifier si l'email est déjà utilisé
            $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "Cet email est déjà utilisé.";
            } else {
                // Insérer l'utilisateur dans la base de données
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("
                    INSERT INTO users (first_name, last_name, email, password, role, user_picture_path, registration_date)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$prenom, $nom, $email, $hashedPassword, $role, $photoPath]);

                $_SESSION['success_message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données : " . $e->getMessage();
        }
    }
}

// Gérer les erreurs et affichage
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='text-red-600'>" . htmlspecialchars($error) . "</p>";
    }
}
// --------------------------
?>



<?php include('../includes/header.php'); ?>

<section class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6" id="login-form">
        <h2 class="text-2xl font-bold text-center text-purple-600 mb-6">Se connecter</h2>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email"  
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                       placeholder="Entrez votre email">
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-medium">Mot de passe</label>
                <input type="password" name="password"  
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                       placeholder="Entrez votre mot de passe">
            </div>
            <div>
                <button type="submit" 
                        class="w-full bg-purple-600 text-white py-3 rounded-lg font-medium hover:bg-purple-700 transition">
                    Se connecter
                </button>
            </div>
            <h5 class="text-center">Vous n'êtes pas inscrit ? <span class="text-purple-900 font-bold cursor-pointer" id="subscribe-btn">S'inscrire</span></h5>
        </form>
    </div>
    <div class="hidden w-full max-w-md bg-white rounded-lg shadow-lg p-6" id="registration-form">
    <h2 class="text-2xl font-bold text-center text-purple-600 mb-6">S'inscrire</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <!-- Nom -->
        <div>
            <label for="nom" class="block text-gray-700 font-medium">Nom</label>
            <input type="text" name="nom" id="nom" 
                   class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                   placeholder="Entrez votre nom" required>
        </div>

        <!-- Prénom -->
        <div>
            <label for="prenom" class="block text-gray-700 font-medium">Prénom</label>
            <input type="text" name="prenom" id="prenom" 
                   class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                   placeholder="Entrez votre prénom" required>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-gray-700 font-medium">Email</label>
            <input type="email" name="email" id="email" 
                   class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                   placeholder="Entrez votre email" required>
        </div>

        <!-- Mot de passe -->
        <div>
            <label for="password" class="block text-gray-700 font-medium">Mot de passe</label>
            <input type="password" name="password" id="password" 
                   class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none"
                   placeholder="Entrez votre mot de passe" required>
        </div>

        <!-- Rôle -->
        <div>
            <label for="role" class="block text-gray-700 font-medium">Rôle</label>
            <select name="role" id="role" 
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none" required>
                <option value="author">Auteur</option>
                <option value="visitor">Visiteur</option>
            </select>
        </div>

        <!-- Photo (facultatif) -->
        <div>
            <label for="photo" class="block text-gray-700 font-medium">Photo (facultatif)</label>
            <input type="file" name="photo" id="photo" 
                   class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none">
        </div>

        <!-- Bouton d'inscription -->
        <div>
            <button type="submit" 
                    class="w-full bg-purple-600 text-white py-3 rounded-lg font-medium hover:bg-purple-700 transition mb-4">
                S'inscrire
            </button>
            <h5 class="text-center">Vous avez déjà un compte ? <span class="text-purple-900 font-bold cursor-pointer" id="connect-btn">Se connecter</span></h5>
        </div>
    </form>
</div>

</section>
<script>
    let subscribeBtn = document.getElementById("subscribe-btn");
    let registrationForm = document.getElementById("registration-form");
    let loginForm = document.getElementById("login-form");
    subscribeBtn.addEventListener("click", () => {
    registrationForm.classList.remove('hidden');
    loginForm.classList.add('hidden');
    });


    let connectBtn = document.getElementById("connect-btn");
    connectBtn.addEventListener("click", () => {
    registrationForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
    });


</script>
<?php include('../includes/footer.php'); ?>
