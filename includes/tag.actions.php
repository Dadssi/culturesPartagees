<?php
require_once '../config/database.php';
require_once '../classes/tag.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $tagName = $_POST['tag_name'] ?? '';

    if (empty($tagName)){
        $errors[] = 'Vous devez Saisir un tag';
    }
    $tag = new tag($tagName);
    $tag -> createTag();
    header("Location: ../public/adminDashboard.php");
}

// ------------------------------------------------------------------

// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $tagName = $_POST['updatedCategoryLabel'] ?? '';

//     if (empty($tagName)){
//         $errors[] = 'Vous devez Saisir une catégorie';
//     }
//     $category = new Category($tagName);
//     $category -> createCategory();
//     header("Location: ../public/adminDashboard.php");
// }