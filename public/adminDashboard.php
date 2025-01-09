<?php
require_once '../config/database.php';
require_once '../classes/user.php';
require_once '../classes/admin.php';
require_once '../classes/category.php';
require_once '../classes/Tag.php';
// require_once '../public/login.php';


session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Récupérer les informations de l'utilisateur
    $userInfo = User::getInfoUser($userId);

    if (!$userInfo) {
        // echo "Bienvenue, " . htmlspecialchars($userInfo['first_name']) . "!<br>";
        // echo "Votre email : " . htmlspecialchars($userInfo['email']) . "<br>";
        // echo "Votre rôle : " . htmlspecialchars($userInfo['role']) . "<br>";
        echo "Impossible de récupérer les informations de l'utilisateur.";
    }
} else {
    echo "Vous devez être connecté pour accéder à cette page.";
    // Redirection vers la page de connexion
    header("Location: login.php");
    exit;
}

// ----------------------------------------------------------------------
$categories = Category::getAllCategories();
$result = category::getTotalCategories();
$totalCategories = $result['total'];
// ----------------------------------------------------------------------
$totalVisitors = User::countUsersByRole('visitor');
$totalAuthors = User::countUsersByRole('author');
// ----------------------------------------------------------------------
$tags = tag::getAllTags();






// ----------------------------------------------------------------------------



$allUsers = User::getAllUsers();

$formattedUsers = [];

foreach ($allUsers as $user) {
    $formattedUsers[] = [
        'username' => $user['last_name'] . ' ' . $user['first_name'], // Concaténez le nom et prénom
        'email' => $user['email'],
        'role' => $user['role'],
        'status' => $user['statut'] ?: '',  // Valeur par défaut si vide
        'articles' => $user['article_count'],
        'registration_date' => $user['registration_date']
    ];
}


// echo $allUsers;


// ----------------------------------------------------------------------------



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register form</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' 
    rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/8dd174d5fa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Marhey:wght@300..700&family=Monoton&family=Montserrat:ital,wght@0,100..900;1,100..900&
    family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&
    family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&
    family=Rock+Salt&
    family=Rubik+Vinyl&
    family=Sixtyfour+Convergence&
    family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        .filter-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .filter-panel.show {
            max-height: 500px;
        }
    </style>

</head>

<body>
    <div>
        <div x-data="setup()" x-init="$refs.loading.classList.add('hidden');">
            <div id="aside-bar" class="flex h-screen antialiased text-gray-900 bg-gray-100">
            <!-- Loading screen -->
                <div x-ref="loading" class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-purple-700">Loading...</div>
                <!-- Sidebar -->
                <div
                    x-transition:enter="transform transition-transform duration-300"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition-transform duration-300"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    x-show="isSidebarOpen"
                    class="fixed inset-y-0 z-10 flex w-80"
                >
                <!-- Curvy shape -->
                <svg
                    class="absolute inset-0 w-full h-full text-white" style="filter: drop-shadow(10px 0 10px #00000030)" preserveAspectRatio="none" viewBox="0 0 309 800" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M268.487 0H0V800H247.32C207.957 725 207.975 492.294 268.487 367.647C329 243 314.906 53.4314 268.487 0Z" />
                </svg>
                <!-- Sidebar content -->
                <div class="z-10 flex flex-col flex-1">
                    <div class="flex items-center justify-around flex-shrink-0 w-64 p-4">
                    <!-- Logo -->
                        <a href="#">
                            <span class="sr-only">CULTURE PARTAGEE LOGO</span>
                            <svg
                                aria-hidden="true"
                                class="w-16 h-auto text-blue-600"
                                viewBox="0 0 96 53"
                                fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <img class="w-36 h-36 rounded-full border border-4 border-purple-600" src="<?php echo htmlspecialchars($userInfo['user_picture_path']); ?>" alt="photo de profil">
                                <h1 class="font-bold text-xl" style="color: purple; font-family:'Rubik Vinyl', serif;">CULTURE PARTAGEE</h1>
                            </svg>
                        </a>
                        <!-- Close btn -->
                        <button @click="isSidebarOpen = false" class="p-1 rounded-lg focus:outline-none focus:ring text-purple-900">
                            <svg
                                class="w-6 h-6"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="sr-only">Close sidebar</span>
                        </button>
                    </div>
                    <nav class="flex flex-col flex-1 w-64 p-4 mt-4 space-y-4">
                        <a href="#" class="flex items-center space-x-3 text-lg" id="quick-statistics-btn">
                            <svg class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="text-gray-800 font-medium">Statistiques Rapides</span>
                        </a>

                        <a href="#" class="flex items-center space-x-3 text-lg" id="categories-btn">
                            <svg class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16m-7 4h7m-7 4h7" />
                            </svg>
                            <span class="text-gray-800 font-medium">Gestion des catégories</span>
                        </a>

                        <a href="#" class="flex items-center space-x-3 text-lg" id="users-btn">
                            <svg class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3a2 2 0 012-2h10a2 2 0 012 2v18l-7-4-7 4V3z" />
                            </svg>
                            <span class="text-gray-800 font-medium">Gestion des utilisateurs</span>
                        </a>

                        <a href="#" class="flex items-center space-x-3 text-lg" id="articles-btn">
                            <svg class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            </svg>
                            <span class="text-gray-800 font-medium">Articles en attente</span>
                        </a>

                        <a href="#" class="flex items-center space-x-3 text-lg" id="statistics-btn">
                            <svg class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5v14M8 9v10m6-6v6m4-10v10" />
                            </svg>
                            <span class="text-gray-800 font-medium">Statistiques</span>
                        </a>
                    </nav>
                    <div class="flex-shrink-0 p-4">
                        <button class="flex items-center space-x-2 cursor-pointer" id="logout-btn">
                            <svg aria-hidden="true" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- end aside bar -->

            <!-- start main container -->
            <main class="flex flex-col flex-1 w-full bg-gray-100">
                <!-- bouton d'affichage de la barre latérale -->
                <section class="flex justify-between bg-gradient-to-l from-purple-950 via-purple-600 to-purple-950 shadow-2xl items-center p-4">
                    <div class="w-[10%]">
                        <button @click="isSidebarOpen = true" class="fixed p-2 text-white bg-purple-600 rounded-lg left-4 lg:left-10 top-6 lg:top-12">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span class="sr-only">Open menu</span>
                        </button>
                    </div>
                    
                    <!---Header of main -------------------------------------------------------------------------------------------  -->
                    <div class="w-[85%]">
                        <div class="flex items-center justify-center mx-auto">
                            <h1 class="text-md md:text-xl lg:text-5xl text-right font-bold text-purple-200" style="font-family:'Rubik Vinyl', serif; letter-spacing:0.5rem;" >CULTURE PARTAGEE</h1>
                        </div>
                        <div class="items-center justify-center">
                            <h2 class="text-sm lg:text-xl font-semi-bold text-white text-center">Bonjour, <br><span class="text-md text-purple-100"><?php echo htmlspecialchars($userInfo['last_name']) . " " . htmlspecialchars($userInfo['first_name']); ?></span></h2>
                        </div>
                    </div>
                </section>
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- ------------------------------------ -->
                <!-- body of main --------------------------------------------------------------------------------------->
                <section class="p-6 w-full p-4 bg-gray-100">
                    <!-- Cartes des statistiques -->
                    <div id="quick-statistics" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 lg:w-4/5 lg:mx-auto">
                        <div class="bg-white shadow-2xl rounded-lg p-4 flex flex-col justify-center items-center h-64 lg:h-96">
                            <h3 class="text-lg lg:text-2xl font-semibold text-gray-600 mb-6">Total des visiteurs</h3>
                            <div class="h-20 w-20 bg-purple-700 rounded-full p-4 flex justify-center items-center">
                                <p class="text-3xl font-bold text-white bg-purple-700 rounded-full"><?php echo htmlspecialchars($totalVisitors)?></p>
                            </div>
                        </div>
                        <div class="bg-white shadow-2xl rounded-lg p-4 flex flex-col justify-center items-center h-64 lg:h-96">
                            <h3 class="text-lg lg:text-2xl font-semibold text-gray-600 mb-6">Total des auteurs</h3>
                            <div class="h-20 w-20 bg-purple-700 rounded-full p-4 flex justify-center items-center">
                                <p class="text-3xl font-bold text-white text-center"><?php echo htmlspecialchars($totalAuthors)?></p>
                            </div>
                        </div>
                        <div class="bg-white shadow-2xl rounded-lg p-4 flex flex-col justify-center items-center h-64 lg:h-96">
                            <h3 class="text-lg lg:text-2xl font-semibold text-gray-600 mb-6">Total des articles</h3>
                            <div class="h-20 w-20 bg-purple-700 rounded-full p-4 flex justify-center items-center">
                                <p class="text-3xl font-bold text-white">32</p>
                            </div>
                        </div>
                        <div class="bg-white shadow-2xl rounded-lg p-4 flex flex-col justify-center items-center h-64 lg:h-96">
                            <h3 class="text-lg lg:text-2xl font-semibold text-gray-600 mb-6">Total des catégories</h3>
                            <div class="h-20 w-20 bg-purple-700 rounded-full p-4 flex justify-center items-center">
                                <p class="text-3xl font-bold text-white"><?php echo htmlspecialchars($totalCategories)?></p>
                            </div>
                        </div>
                    </div>
                    <!-- ----------------- -->
                    <!-- ----------------- -->
                    <!-- ----------------- -->
                    <!-- ----------------- -->
                    <!-- ----------------- -->
                    <!-- ----------------- -->



                    <!-- gestion des catégories------------------------------------------------------------------------ -->
                    <div id="manage-categories" class="section hidden w-1/2 mx-auto">
                        <h3 class="text-lg font-bold text-purple-700 mb-4">Liste des catégories :</h3>
                        <div class="overflow-x-auto p-4 rounded-lg shadow-md mb-16">
                            <table class="table-auto w-full border-collapse border border-gray-200">
                                <thead class="bg-purple-700 text-white">
                                    <tr>
                                        <th class="px-4 py-2 border border-gray-300">Catégories</th>
                                        <th class="px-4 py-2 border border-gray-300" style="width: 25%;">Gérer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categories as $category) :?>
                                    <tr class="odd:bg-purple-50 even:bg-purple-100 hover:bg-purple-200">
                                        <td class="px-4 py-2 border border-gray-300"><?php echo htmlspecialchars($category['label']); ?></td>
                                        <td class="px-4 py-2 border border-gray-300" style="width: 25%;">
                                        <div class="flex gap-4 justify-around">
                                            <button class="bg-blue-400 text-white px-2 py-1 rounded hover:bg-blue-600">Modifier</button>
                                            <button class="bg-red-400 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</button>
                                        </div>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Bouton Ajouter catégorie -->
                            <div class="flex justify-center mt-4">
                                <button onclick="openModal('categoryModal')" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                                    Ajouter catégorie
                                </button>
                            </div>
                            <!-- Modal pour ajouter la catégorie -->
                            <div id="categoryModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                                <div class="bg-white p-6 rounded-lg w-1/3">
                                    <h2 class="text-xl font-semibold text-center mb-4">Ajouter une catégorie</h2>
                                    <form id="categoryForm" action ="../includes/categories-actions.php" method="post">
                                        <div class="mb-4">
                                            <label for="categoryName" class="block text-gray-700">Nom de la catégorie</label>
                                            <input type="text" id="categoryName" class="w-full px-4 py-2 border border-gray-300 rounded" required name="categoryLabel">
                                        </div>
                                        <div class="flex justify-between">
                                            <button type="button" onclick="closeModal('categoryModal')" class="bg-gray-400 text-white px-2 py-2 rounded hover:bg-gray-500 mx-2">Annuler</button>
                                            <button type="submit" class="bg-purple-500 text-white px-2 py-2 rounded hover:bg-purple-600">Ajouter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <h1 class="text-lg text-purple-700 font-bold mb-6">Gestion des tags :</h1>
                        <div class="mb-4">
                            <h2 class="mb-4 text-lg text-purple-950"> + Ajouter Tag :</h2>
                            <form action="../includes/tag.actions.php" method="POST" class="flex">
                                <label for="tag_name"></label>
                                <input type="text" id="tag_name" name="tag_name" class="w-1/2 px-4 py-2 border border-gray-300 rounded bg-gray-100" required name="categoryLabel">
                                <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 w-1/4">
                                    Ajouter Tag
                                </button>
                            </form>
                        </div>
                        <h2 class="my-4 font-bold text-rose-700">Liste des tags :</h2>
                        <div class="width3/4 rounded shadow-xl bg-purple-200 min-h-48 p-8">
                            <?php foreach($tags as $tag) : ?>
                            <span id="badge-dismiss-dark" class="inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-gray-200 bg-gray-100 rounded dark:bg-gray-700 mr-2">
                                <?php echo htmlspecialchars($tag['tag_title']); ?>
                                <button type="button" class="inline-flex items-center p-1 ms-2 text-sm text-gray-400 bg-transparent rounded-sm hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-gray-300" data-dismiss-target="#badge-dismiss-dark" aria-label="Remove">
                                    <svg class="w-2 h-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Remove badge</span>
                                </button>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                 



                    <!-- Gestion des utilisateurs -->
                    <div id="manage-users" class="section hidden">
                        <div class="p-6 max-w-6xl mx-auto bg-white rounded-lg shadow-lg my-8">
                            <!-- En-tête avec recherche et filtres -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-2xl font-bold text-purple-800">Gestion des Utilisateurs :</h2>
                                    <button id="filterToggle" class="flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-700 rounded-lg transition-colors text-gray-100">
                                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 4h18M3 12h18M3 20h18"></path>
                                        </svg>
                                        Filtres
                                        <svg class="w-4 h-4 ml-2 transform transition-transform duration-200" id="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Barre de recherche -->
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="searchInput"
                                        placeholder="Rechercher un utilisateur..."
                                        class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="M21 21l-4.35-4.35"></path>
                                    </svg>
                                </div>

                                <!-- Panneau de filtres -->
                                <div class="filter-panel mt-4" id="filterPanel">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Statut</option>
                                            <option value="actif">Actif</option>
                                            <option value="suspendu">Suspendu</option>
                                        </select>
                                        
                                        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Rôle</option>
                                            <option value="visitor">Visiteur</option>
                                            <option value="author">Auteur</option>
                                            <option value="admin">administrateur</option>
                                        </select>
                                        
                                        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Nombre d'articles</option>
                                            <option value="0-5">0-10 articles</option>
                                            <option value="6-15">11-20 articles</option>
                                            <option value="15+">20+ articles</option>
                                        </select>
                                        
                                        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Date d'inscription</option>
                                            <option value="today">Aujourd'hui</option>
                                            <option value="week">Cette semaine</option>
                                            <option value="month">Ce mois</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tableau -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Articles</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'inscription</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200" id="userTableBody">
                                    </tbody>
                                </table>
                            </div>
                            <!-- modal de modification des catégories -->
                            <div id="updateCategoryModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                                <div class="bg-white p-6 rounded-lg w-1/2">
                                    <h2 class="text-xl font-semibold text-center mb-4">Modifier cette catégorie</h2>
                                    <!-- <form id="categoryForm" action ="../includes/categories-actions.php" method="post"> -->
                                    <div class="mb-4">
                                        <label for="categoryName" class="block text-gray-700">Nom de la catégorie</label>
                                        <input type="text" id="categoryName" class="w-full px-4 py-2 border border-gray-300 rounded" required name="updatedCategoryLabel">
                                    </div>
                                    <div class="flex justify-between">
                                        <button type="button" onclick="closeModal(updateCategoryModal)" class="bg-gray-400 text-white px-2 py-2 rounded hover:bg-gray-500 mx-2">Annuler</button>
                                        <button type="submit" class="bg-purple-500 text-white px-2 py-2 rounded hover:bg-purple-600">Modifier</button>
                                    </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="flex items-center justify-between mt-6">
                                <div class="text-sm text-gray-700">
                                    Affichage de 1 à 5 sur 50 résultats
                                </div>
                                <div class="flex gap-2">
                                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Précédent</button>
                                    <button class="px-3 py-1 border border-gray-300 rounded-md bg-blue-500 text-white">1</button>
                                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">2</button>
                                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">3</button>
                                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Suivant</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Gestion des articles -->
                    <div id="manage-articles" class="section hidden mt-8">
                        <h3 class="text-lg font-semibold text-gray-600 mb-4">Gérer les articles</h3>
                        <table class="w-full bg-white shadow rounded-lg overflow-hidden">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left p-3 text-gray-700 font-medium">User</th>
                                    <th class="text-left p-3 text-gray-700 font-medium">Action</th>
                                    <th class="text-left p-3 text-gray-700 font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-3 border-t text-gray-600">John Doe</td>
                                    <td class="p-3 border-t text-gray-600">Logged in</td>
                                    <td class="p-3 border-t text-gray-600">2025-01-03</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border-t text-gray-600">Jane Smith</td>
                                    <td class="p-3 border-t text-gray-600">Added a post</td>
                                    <td class="p-3 border-t text-gray-600">2025-01-03</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>




                    <!-- Statistiques -->
                    <div id="statistics" class="section hidden mt-8" >
                        <h3 class="text-lg font-semibold text-gray-600 mb-4">Les statistiques</h3>
                        <table class="w-full bg-white shadow rounded-lg overflow-hidden">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left p-3 text-gray-700 font-medium">User</th>
                                    <th class="text-left p-3 text-gray-700 font-medium">Action</th>
                                    <th class="text-left p-3 text-gray-700 font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-3 border-t text-gray-600">John Doe</td>
                                    <td class="p-3 border-t text-gray-600">Logged in</td>
                                    <td class="p-3 border-t text-gray-600">2025-01-03</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border-t text-gray-600">Jane Smith</td>
                                    <td class="p-3 border-t text-gray-600">Added a post</td>
                                    <td class="p-3 border-t text-gray-600">2025-01-03</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>




                </section>
            </main>
        </div>
        <!-- partie JavaScript------------------------------------------------------------------ -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.8.1/alpine.js"></script>
        <script>
            const setup = () => {
            return {
                    isSidebarOpen: false,
                }
            };

            // ------------------------------------------------

            


       
    //         document.querySelectorAll('button[data-section]').forEach(button => {
    //     button.addEventListener('click', () => {
    //         const targetSection = button.getAttribute('data-section');

    //         // Masquer toutes les sections
    //         document.querySelectorAll('.section').forEach(section => {
    //             section.classList.add('hidden');
    //         });

    //         // Afficher la section cible
    //         document.getElementById(targetSection).classList.remove('hidden');
    //     });
    // });









        let quickStatisticsBtn = document.getElementById("quick-statistics-btn");
        let categoriesBtn = document.getElementById("categories-btn");
        let usersBtn = document.getElementById("users-btn");
        let articlesBtn = document.getElementById("articles-btn");
        let statisticsBtn = document.getElementById("statistics-btn");

        let quickStatistics = document.getElementById("quick-statistics");
        let categories = document.getElementById("manage-categories");
        let manageUsers = document.getElementById("manage-users");
        let articles = document.getElementById("manage-articles");
        let statistics = document.getElementById("statistics");

        categoriesBtn.addEventListener("click", ()=> {
            categories.classList.remove("hidden");
            manageUsers.classList.add("hidden");
            articles.classList.add("hidden");
            statistics.classList.add("hidden");
            quickStatistics.classList.add("hidden");
        });

        usersBtn.addEventListener("click", ()=> {
            categories.classList.add("hidden");
            manageUsers.classList.remove("hidden");
            articles.classList.add("hidden");
            statistics.classList.add("hidden");
            quickStatistics.classList.add("hidden");
        });

        articlesBtn.addEventListener("click", ()=> {
            categories.classList.add("hidden");
            manageUsers.classList.add("hidden");
            articles.classList.remove("hidden");
            statistics.classList.add("hidden");
            quickStatistics.classList.add("hidden");
        });

        statisticsBtn.addEventListener("click", ()=> {
            categories.classList.add("hidden");
            manageUsers.classList.add("hidden");
            articles.classList.add("hidden");
            statistics.classList.remove("hidden");
            quickStatistics.classList.add("hidden");
        });

        quickStatisticsBtn.addEventListener("click", ()=> {
            quickStatistics.classList.remove("hidden");
            categories.classList.add("hidden");
            manageUsers.classList.add("hidden");
            articles.classList.add("hidden");
            statistics.classList.add("hidden");
        });
        // ----------------------------------------------------
        // Fonction pour ouvrir la modal
        function openModal(modalID) {
            document.getElementById(modalID).classList.remove('hidden');
        }

        // Fonction pour fermer la modal
        function closeModal(modalID) {
            document.getElementById(modalID).classList.add('hidden');
        }

        // Fonction pour gérer la soumission du formulaire (vous pouvez ici envoyer les données à votre serveur)
        // document.getElementById('categoryForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     const categoryName = document.getElementById('categoryName').value;
        //     console.log("Nouvelle catégorie :", categoryName);
        //     // Ajoutez ici le code pour envoyer la catégorie au serveur
        //     closeModal();  // Fermer la modal après la soumission
        //     document.getElementById('categoryForm').submit();
        // });
        // ---------------------------------------------------------
        
            
            const utilisateurs = [
        {username: "Mohamed Abdelhak DADSSI", email: "d4dssi@gmail.com", role: "admin", status: "Inactif", articles: 0, registration_date: "2025-01-02" },
        {username: "amine khalid", email: "khalid@gmail.com", role: "visitor", status: "Inactif", articles: 0, registration_date: "2025-01-02" },
        {username: "mehdi essadik", email: "essadik@gmail.com", role: "visitor", status: "Inactif", articles: 0, registration_date: "2025-01-02" },
        {username: "zakaria bahou", email: "bahou@gmail.com", role: "author", status: "active", articles: 0, registration_date: "2025-01-02" },
        {username: "abdelhak dadssi", email: "abdelhak@gmail.com", role: "visitor", status: "Inactif", articles: 0, registration_date: "2025-01-03" },
        {username: "hamza derkaoui", email: "derkaoui@gmail.com", role: "author", status: "active", articles: 0, registration_date: "2025-01-05" },
        {username: "karim alaoui", email: "alaoui@gmail.com", role: "author", status: "active", articles: 0, registration_date: "2025-01-05" },
            ]
            



            const users = <?php echo json_encode($formattedUsers); ?>;
            console.log(users);



            // Gestion du panneau de filtres
            const filterToggle = document.getElementById('filterToggle');
            const filterPanel = document.getElementById('filterPanel');
            const chevron = document.getElementById('chevron');

            filterToggle.addEventListener('click', () => {
                filterPanel.classList.toggle('show');
                chevron.style.transform = filterPanel.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
            });

            // Fonction pour rendre le statut avec la bonne couleur
            function getStatusBadge(status) {
                const colors = {
                    'actif': 'bg-green-100 text-green-800',
                    'suspendu': 'bg-red-100 text-red-800'
                };
                return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${colors[status]}">${status}</span>`;
            }
          

            // Fonction pour rendre le tableau
            function renderUsers(users) {
                const tbody = document.getElementById('userTableBody');
                tbody.innerHTML = users.map((user, index) => `
                    <tr class="hover:bg-purple-200  ${index % 2 === 0 ? 'bg-purple-50' : 'bg-purple-100'}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">${user.username}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">${user.email}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                ${user.role}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${getStatusBadge(user.status)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">${user.articles}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">${user.registration_date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openModal(categoryModal)" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</button>
                            <button class="text-red-600 hover:text-red-900">Supprimer</button>
                        </td>
                    </tr>
                `).join('');
            }

            // Fonction de recherche
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const filteredUsers = users.filter(user => 
                    user.username.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm)
                );
                renderUsers(filteredUsers);
            });

            // Fonctions pour les actions (à implémenter selon vos besoins)
            function editUser(id) {
                console.log('Éditer utilisateur:', id);
            }

            function deleteUser(id) {
                if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                    console.log('Supprimer utilisateur:', id);
                }
            }

            // Rendu initial du tableau
            renderUsers(users);

            const logoutBtn = document.getElementById('logout-btn');
            logoutBtn.addEventListener("click", () => {
                window.location.href = '../includes/logout.php';
            });
    

        
        </script>
    </div>
</body>
        </html>