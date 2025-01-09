<?php
// require_once '../config/database.php';
// require_once '../classes/category.php';

// $errors = [];

// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $categoryLabel = $_POST['categoryLabel'] ?? '';

//     if (empty($categoryLabel)){
//         $errors[] = 'Vous devez Saisir une catégorie';
//     }
//     $category = new Category($categoryLabel);
//     $category -> createCategory();
//     header("Location: ../public/adminDashboard.php#categoryModal");
// }

// ------------------------------------------------------------------

// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $categoryLabel = $_POST['updatedCategoryLabel'] ?? '';

//     if (empty($categoryLabel)){
//         $errors[] = 'Vous devez Saisir une catégorie';
//     }
//     $category = new Category($categoryLabel);
//     $category -> createCategory();
//     header("Location: ../public/adminDashboard.php");
// }
// ---------------------------------------------------------------------------------------------------


require_once '../config/database.php';
require_once '../classes/category.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryLabel = $_POST['categoryLabel'] ?? '';

    // Vérification si le champ est vide
    if (empty($categoryLabel)) {
        $errors[] = 'Vous devez saisir une catégorie.';
    } else {
        // Création d'une instance de la classe Category
        $category = new Category($categoryLabel);

        // Vérifier si la catégorie existe déjà
        if ($category->exists()) {
            $errors[] = 'Cette catégorie existe déjà dans la base de données.';
        } else {
            // Ajouter la catégorie si elle n'existe pas
            $category->createCategory();
            header("Location: ../public/adminDashboard.php");
            exit;
        }
    }
}





























?>