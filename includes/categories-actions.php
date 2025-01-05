<?php
require_once '../config/database.php';
require_once '../classes/category.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $categoryLabel = $_POST['categoryLabel'] ?? '';

    if (empty($categoryLabel)){
        $errors[] = 'Vous devez Saisir une catégorie';
    }
    $category = new Category($categoryLabel);
    $category -> createCategory();
    header("Location: ../public/adminDashboard.php");
}

// ------------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $categoryLabel = $_POST['updatedCategoryLabel'] ?? '';

    if (empty($categoryLabel)){
        $errors[] = 'Vous devez Saisir une catégorie';
    }
    $category = new Category($categoryLabel);
    $category -> createCategory();
    header("Location: ../public/adminDashboard.php");
}



























?>