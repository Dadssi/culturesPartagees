<?php
require_once '../config/database.php';
require_once '../classes/user.php';
require_once '../classes/admin.php';
require_once '../classes/category.php';
require_once '../classes/tag.php';
require_once '../classes/article.php';


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
// ---------------------------------------------------------------------------
$articles = Article::getAllArticles();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Marhey:wght@300..700&family=Monoton&family=Montserrat:ital,wght@0,100..900;1,100..900&
    family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&
    family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&
    family=Rock+Salt&
    family=Rubik+Vinyl&
    family=Sixtyfour+Convergence&
    family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-l from-rose-950 via-rose-600 to-rose-950 text-white h-screen fixed p-2">
        <div class="flex justify-center items-center mx-auto">
            <img src="<?php echo htmlspecialchars($userInfo['user_picture_path']); ?>" alt="photo de profil" class="rounded-full w-44 h-44 border border-rose-950 border-4">
        </div>
        <div class="p-4 text-center">
            <h1 class="text-lg font-bold"><?php echo htmlspecialchars($userInfo['last_name']) . " " . htmlspecialchars($userInfo['first_name']); ?></h1>
        </div>
        <nav class="mt-6">
            <ul>
                <li class="p-3 my-1 hover:bg-rose-500 border border-2 border-rose-950 cursor-pointer"><a href="#">Mon Profil</a></li>
                <li class="p-3 my-1 hover:bg-rose-500 border border-2 border-rose-950 cursor-pointer"><a href="#">les règles du Blog</a></li>
                <li class="p-3 my-1 bg-rose-700 hover:bg-rose-500 border border-2 border-rose-950 cursor-pointer" id="logout-btn"><a href="#">Déconnexion</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6">
        <section class="bg-gray-50 px-4 antialiased flex flex-col">
            <div class="flex justify-center items-center p-10 bg-gray-100 w-full shadow-xl h-48">
                <h1 class="text-4xl" style="font-family: 'roboto';">Bonjour,<span><?php echo htmlspecialchars($userInfo['last_name']) . " " . htmlspecialchars($userInfo['first_name']); ?></span></h1>
            </div>
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <!-- Heading & Filters -->
                <div class="mb-4 items-end justify-between sm:flex sm:space-y-0 md:mb-8">
                    <div>
                        <h2 class="mt-3 text-xl font-semibold text-rose-900 sm:text-2xl">Nos articles :</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="sortDropdownButton1" data-dropdown-toggle="dropdownSort1" type="button" class="flex w-full items-center justify-center rounded-lg border border-rose-600 px-3 py-2 font-medium text-gray-300 bg-rose-600 hover:bg-rose-800 hover:text-white focus:z-10 focus:outline-none focus:ring-4 focus:ring-rose-100 sm:w-auto">
                            Afficher par :
                            <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="dropdownSort1" class="z-50 hidden w-40 divide-y divide-gray-100 rounded-lg bg-rose-600 shadow" data-popper-placement="bottom">
                            <ul class="p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400" aria-labelledby="sortDropdownButton">
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> The most popular </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Newest </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Increasing price </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Decreasing price </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> No. reviews </a>
                                </li>
                                <li>
                                    <a href="#" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-200 bg-rose-600 hover:bg-rose-900 hover:text-white"> Discount % </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <?php foreach ($articles as $article) : ?>
                <div class="mb-4 grid gap-4 sm:grid-cols-1 md:mb-8 lg:grid-cols-3 xl:gap-10 xl:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-2xl">
                        <div class="h-56 w-full">
                            <a href="#">
                                <img class="" src="<?php echo htmlspecialchars($article['article_picture_path']) ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" />
                                <!-- <img class="mx-auto hidden h-full dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg" alt="" /> -->
                            </a>
                        </div>
                        <div class="pt-6">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <span class="me-2 rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800">Catégorie : <span class="font-bold">Médecine</span></span>
                                <div class="flex items-center justify-end gap-1">
                                <button type="button" data-tooltip-target="tooltip-reading-time" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                                    <span class="sr-only"> Reading Time </span>
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" />
                                        <path stroke="currentColor" stroke-width="2" d="M12 7v5l3 3" />
                                    </svg>
                                </button>
                                <button type="button" data-tooltip-target="tooltip-add-to-favorites" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 flex">
                                    <span class="sr-only"> Add to Favorites </span>
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500">(455)</span>
                                </button>
                                <div id="tooltip-add-to-favorites" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
                                    Add to favorites
                                    <div class="tooltip-arrow" data-popper-arrow=""></div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="text-xl font-bold leading-tight text-purple-600"><?php echo htmlspecialchars($article['title']); ?></a>
                        <div>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus, quis deserunt doloremque inventore dolores, eum unde, magni quae id ipsa dolorem! Omnis, maiores vitae suscipit est ipsa sint veritatis facilis?
                            </p>
                        </div>
                        <div class="flex w-full">
                            <button type="button" class="mx-auto rounded-lg px-2 py-1 mt-2 text-sm font-medium text-gray-500 hover:text-purple-700">
                                Continuer la lecture..
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <div class="w-full text-center">
            <button type="button" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Show more</button>
        </div>
    </main>
    <img class="" src="<?php echo htmlspecialchars($article['article_picture_path']) ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>
        const logoutBtn = document.getElementById('logout-btn');
            logoutBtn.addEventListener("click", () => {
                window.location.href = '../includes/logout.php';
            });
    </script>
</body>
</html>
