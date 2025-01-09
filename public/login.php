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
<!-- -------------------------------------------------------- -->
<!-- <section class="gradient-form h-full bg-neutral-200">
  <div class="container h-full p-10">
    <div
      class="flex h-full flex-wrap items-center justify-center text-neutral-800">
      <div class="w-full">
        <div
          class="block rounded-lg bg-white shadow-lg">
          <div class="g-0 lg:flex lg:flex-wrap"> -->
            <!-- Left column container-->
            <!-- <div class="px-4 md:px-0 lg:w-6/12"> -->
              <!-- <div class="md:mx-6 md:p-12"> -->
                <!--Logo-->
                <!-- <div class="text-center">
                  <img
                    class="mx-auto w-48"
                    src="https://tecdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                    alt="logo" />
                  <h4 class="mb-12 mt-1 pb-1 text-xl font-semibold">
                    We are The Lotus Team
                  </h4>
                </div>

                <form>
                  <p class="mb-4">Please login to your account</p><div class="text-center"> -->
                  <!-- <img
                    class="mx-auto w-48"
                    src="https://tecdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                    alt="logo" />
                  <h4 class="mb-12 mt-1 pb-1 text-xl font-semibold">
                    We are The Lotus Team
                  </h4>
                </div>

                <form>
                  <p class="mb-4">Please login to your account</p> -->
                  <!--Username input-->
                  <!-- <div class="relative mb-4" data-twe-input-wrapper-init>
                    <input
                      type="text"
                      class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                      id="exampleFormControlInput1"
                      placeholder="Username" />
                    <label
                      for="exampleFormControlInput1"
                      class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none"
                      >Username
                    </label>
                  </div> -->

                  <!--Password input-->
                  <!-- <div class="relative mb-4" data-twe-input-wrapper-init>
                    <input
                      type="password"
                      class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[twe-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none [&:not([data-twe-input-placeholder-active])]:placeholder:opacity-0"
                      id="exampleFormControlInput11"
                      placeholder="Password" />
                    <label
                      for="exampleFormControlInput11"
                      class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[twe-input-state-active]:-translate-y-[0.9rem] peer-data-[twe-input-state-active]:scale-[0.8] motion-reduce:transition-none"
                      >Password
                    </label>
                  </div> -->

                  <!--Submit button-->
                  <!-- <div class="mb-12 pb-1 pt-1 text-center">
                    <button
                      class="mb-3 inline-block w-full rounded px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-dark-3 transition duration-150 ease-in-out hover:shadow-dark-2 focus:shadow-dark-2 focus:outline-none focus:ring-0 active:shadow-dark-2"
                      type="button"
                      data-twe-ripple-init
                      data-twe-ripple-color="light"
                      style="
                        background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
                      ">
                      Log in
                    </button> -->

                    <!--Forgot password link-->
                    <!-- <a href="#!">Forgot password?</a>
                  </div> -->

                  <!--Register button-->
                  <!-- <div class="flex items-center justify-between pb-6">
                    <p class="mb-0 me-2">Don't have an account?</p>
                    <button
                      type="button"
                      class="inline-block rounded border-2 border-danger px-6 pb-[6px] pt-2 text-xs font-medium uppercase leading-normal text-danger transition duration-150 ease-in-out hover:border-danger-600 hover:bg-danger-50/50 hover:text-danger-600 focus:border-danger-600 focus:bg-danger-50/50 focus:text-danger-600 focus:outline-none focus:ring-0 active:border-danger-700 active:text-danger-700"
                      data-twe-ripple-init
                      data-twe-ripple-color="light">
                      Register
                    </button>
                  </div>
                </form>
              </div>
            </div> -->

            <!-- Right column container with background and description-->
            <!-- <div
              class="flex items-center rounded-b-lg lg:w-6/12 lg:rounded-e-lg lg:rounded-bl-none"
              style="background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593)">
              <div class="px-4 py-6 text-white md:mx-6 md:p-12">
                <h4 class="mb-6 text-xl font-semibold">
                  We are more than just a company
                </h4>
                <p class="text-sm">
                  Lorem ipsum dolor sit amet, consectetur adipisicing
                  elit, sed do eiusmod tempor incididunt ut labore et
                  dolore magna aliqua. Ut enim ad minim veniam, quis
                  nostrud exercitation ullamco laboris nisi ut aliquip ex
                  ea commodo consequat.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->
<!-- -------------------------------------------------------- -->
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
