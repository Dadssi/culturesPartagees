<?php
require_once '../config/database.php';
require_once '../classes/user.php';
require_once '../classes/admin.php';
require_once '../classes/category.php';
require_once '../classes/tag.php';
// require_once '../public/login.php';


session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Récupérer les informations de l'utilisateur
    $userInfo = User::getInfoUser($userId);

    if (!$userInfo) {
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
// ----------------------------------------------------------------------
$tags = tag::getAllTags();
// ----------------------------------------------------------------------






// ----------------------------------------------------------------------------







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

</head>

<body>
    

    <!-- Navbar End -->

<!-- Sidebar -->
<aside class="w-64 bg-gradient-to-l from-purple-950 via-purple-600 to-purple-950 text-white h-screen fixed">
        <div class="p-4 text-center">
            <h1 class="text-lg font-bold">DASHBOARD AUTEUR</h1>
        </div>
        <nav class="mt-6">
            <ul>
                <li class="p-3 mx-2 my-1 border border-1 border-purple-950 hover:bg-purple-500 cursor-pointer" id="show-my-articles" data-section="add-article-section">Mes articles</li>
                <li class="p-3 mx-2 my-1 border border-1 border-purple-950 hover:bg-purple-500 cursor-pointer" id="add-article-btn" data-section="manage-users">ajouter un article</li>
                <li class="p-3 mx-2 my-1 border border-1 border-purple-950 hover:bg-purple-500 cursor-pointer" id="articles-btn" data-section="manage-articles">Articles en attente</li>
                <li class="p-3 mx-2 my-1 border border-1 border-purple-950 hover:bg-purple-500 cursor-pointer" id="statistics-btn" data-section="statistics">Mon Profile</li>
                <li class="p-3 mx-2 my-1 border border-1 border-purple-950 bg-purple-950 hover:bg-purple-500 cursor-pointer" ><a href="../includes/logout.php"><i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Déconnexion</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6 mb-96">
        <!-- Header -->
        <header class="mb-6 h-48 bg-gradient-to-l from-purple-950 via-purple-600 to-purple-950 flex items-center justify-between px-4 py-4 shadow-2xl">
            <h2 class="text-xl font-bold text-white">Bonjour, <?php echo htmlspecialchars($userInfo['last_name']) . " " . htmlspecialchars($userInfo['first_name']); ?></h2>
            <h1 class="text-5xl font-bold text-purple-200 drop-shadow-[0_35px_35px_rgba(107,33,168,0.9)]">CULTURE PARTAGEE</h1>
            <img class="w-36 rounded-full border border-4 border-purple-600" src="<?php echo htmlspecialchars($userInfo['user_picture_path']); ?>" alt="photo de profil">
        </header>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white shadow-2xl rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">Total de mes articles</h3>
                <p class="text-2xl font-bold text-purple-600">23</p>
            </div>
            <div class="bg-white shadow-2xl rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">Articles en attente</h3>
                <p class="text-2xl font-bold text-purple-600">23</p>
            </div>
            <div class="bg-white shadow-2xl rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">Articles récement approuvés</h3>
                <p class="text-2xl font-bold text-purple-600">32</p>
            </div>
            <div class="bg-white shadow-2xl rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">Articles rejetés</h3>
                <p class="text-2xl font-bold text-purple-600">99</p>
            </div>
        </div>

        <!-- gestion des catégories -->
        <div id="manage-categories" class="section hidden mt-8">
            <h3 class="text-lg font-semibold text-purple-500 mb-4">Liste des catégories</h3>
            <div class="overflow-x-auto p-4 bg-white rounded-lg shadow-md mb-52">
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
                            <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-green-600">Modifier</button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</button>
                        </div>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
                </table>

                <!-- Bouton Ajouter catégorie -->
                <div class="flex justify-center mt-4">
                    <button onclick="openModal(categoryModal)" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                        Ajouter catégorie
                    </button>
                </div>

                <!-- Modal pour ajouter la catégorie -->
                <div id="categoryModal" class="hidden fixed inset-0 w-1/3 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg w-1/2">
                        <h2 class="text-xl font-semibold text-center mb-4">Ajouter une catégorie</h2>
                        <form id="categoryForm" action ="../includes/categories-actions.php" method="post">
                        <div class="mb-4">
                            <label for="categoryName" class="block text-gray-700">Nom de la catégorie</label>
                            <input type="text" id="categoryName" class="w-full px-4 py-2 border border-gray-300 rounded" required name="categoryLabel">
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="closeModal(categoryModal)" class="bg-gray-400 text-white px-2 py-2 rounded hover:bg-gray-500 mx-2">Annuler</button>
                            <button type="submit" class="bg-purple-500 text-white px-2 py-2 rounded hover:bg-purple-600">Ajouter</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ajouter un article -->
        <div id="add-article" class="section mt-8 w-3/4 mx-auto">
            <h2 class="font-bold text-purple-800 mb-10 text-2xl">Ajouter un article :</h2>
            <!-- Modal body -->
            <form action="#">
                <div class="flex flex-col mb-4">
                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900"></label>
                        <input type="text" name="title" id="title" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Titre de l'article">
                    </div>
                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900"></label>
                        <select id="category" name="category" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                            <option selected="">Choisir une catégorie</option>
                            <!-- inserer une forEach PHP -->
                            <?php foreach($categories as $category) :?>
                            <option value="<?php echo htmlspecialchars($category['label']); ?>"><?php echo htmlspecialchars($category['label']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="tags" class="block my-4 text-sm font-medium text-gray-900">
                            Choisissez un ou plusieurs tags :
                        </label>
                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-2.5">
                            <?php foreach($tags as $tag) :?>
                            <div class="flex items-center mb-2">
                                <input id="" type="checkbox" name="tags[]" value="" 
                                    class="w-4 h-4 text-primary-500 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2">
                                <label for="" class="ml-2 text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($tag['tag_title']); ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="article-text" class="block mb-2 text-sm font-medium text-gray-900"></label>
                        <textarea id="article-text" name="article-text" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-100 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Veuillez écrire votre aerticle ici..."></textarea>                    
                    </div>
                    <div class="my-4">
                        <label for="article-img" class="block mb-2 text-sm font-medium text-gray-900">Veuillez choisir une image à votre article :</label>
                        <input type="file" name="article-img" id="article-img" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>
                </div>
                <div class="flex items-center space-x-4 w-1/2 mx-auto">
                    <!-- <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Update product
                    </button> -->
                    <button type="button" class="text-white text-lg inline-flex justify-center items-center hover:text-white border border-green-600 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <svg class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Ajouter
                    </button>

                </div>
            </form>
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
    </main>





    <script>
      let addArticleBtn = document.getElementById("show-my-articles");
      let usersBtn = document.getElementById("add-article-btn");
      let articlesBtn = document.getElementById("articles-btn");
      let statisticsBtn = document.getElementById("statistics-btn");

      let gategories = document.getElementById("add-article-section");
      let manageUsers = document.getElementById("manage-users");
      let articles = document.getElementById("manage-articles");
      let statistics = document.getElementById("statistics");

      categoriesBtn.addEventListener("click", ()=> {
        gategories.classList.remove("hidden");
        manageUsers.classList.add("hidden");
        articles.classList.add("hidden");
        statistics.classList.add("hidden");
      });

      usersBtn.addEventListener("click", ()=> {
        gategories.classList.add("hidden");
        manageUsers.classList.remove("hidden");
        articles.classList.add("hidden");
        statistics.classList.add("hidden");
      });

      articlesBtn.addEventListener("click", ()=> {
        gategories.classList.add("hidden");
        manageUsers.classList.add("hidden");
        articles.classList.remove("hidden");
        statistics.classList.add("hidden");
      });

      statisticsBtn.addEventListener("click", ()=> {
        gategories.classList.add("hidden");
        manageUsers.classList.add("hidden");
        articles.classList.add("hidden");
        statistics.classList.remove("hidden");
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
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const categoryName = document.getElementById('categoryName').value;
        console.log("Nouvelle catégorie :", categoryName);
        // Ajoutez ici le code pour envoyer la catégorie au serveur
        closeModal();  // Fermer la modal après la soumission
        document.getElementById('categoryForm').submit();
    });
    // ---------------------------------------------------------
    
        
        const utilisateurs = [
    {username: "Mohamed Abdelhak DADSSI", email: "d4dssi@gmail.com", role: "admin", status: "Inactif", articles: 0, registration_date: "2025-01-02" },
    {username: "amine khalid", email: "khalid@gmail.com", role: "visitor", status: "Inactif", articles: 0, registration_date: "2025-01-02" },
    {username: "mehdi essadik", email: "essadik@gmail.com", role: "visitor", status: "Inactif", articles: 0, registration_date: "2025-01-02" },
    {username: "zakaria bahou", email: "bahou@gmail.com", role: "author", status: "active", articles: 0, registration_date: "2025-01-02" },
    {username: "abdelhak dadssi", email: "abdelhak@gmail.com", role: "visitor", status: "Inactif", articles: 0, registration_date: "2025-01-03" },
    {username: "hamza derkaoui", email: "derkaoui@gmail.com", role: "author", status: "active", articles: 0, registration_date: "2025-01-05" },
    {username: "karim alaoui", email: "alaoui@gmail.com", role: "author", status: "active", articles: 0, registration_date: "2025-01-05" },
        ];
        console.log(utilisateurs);



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
                'Actif': 'bg-green-100 text-green-800',
                'Suspendu': 'bg-red-100 text-red-800'
            };
            return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${colors[status]}">${status}</span>`;
        }

        // Fonction pour rendre le tableau
        function renderUsers(users) {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = users.map((user, index) => `
                <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
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

        const logoutBtn = document.getElementById('author-logout-btn');
        logoutBtn.addEventListener("click", () => {
            console.log("salam");
            window.location.href = '../includes/logout.php';
        });
   

      
    </script>



<?php
include('../includes/footer.php');
?>