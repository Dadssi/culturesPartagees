<?php
require_once '../config/database.php';
require_once '../classes/tag.php';

// $errors = [];

// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $tagName = $_POST['tag_name'] ?? '';

//     if (empty($tagName)){
//         $errors[] = 'Vous devez Saisir un tag';
//     }
//     $tag = new tag($tagName);
//     $tag -> createTags();
//     header("Location: ../public/adminDashboard.php");
// }

// ------------------------------------------------------------------

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tags = $_POST['tags'] ?? []; // Récupère le tableau des tags

    if (empty($tags) || !is_array($tags)) {
        $errors[] = 'Vous devez saisir au moins un tag.';
    } else {
        $cleanedTags = array_filter(array_map('trim', $tags)); // Nettoie les espaces et retire les valeurs vides

        if (empty($cleanedTags)) {
            $errors[] = 'Tous les champs de tags sont vides.';
        } else {
            $tag = new Tag(); // Instancie l'objet Tag
            $tag->createTags($cleanedTags); // Passe le tableau nettoyé à la méthode
            header("Location: ../public/adminDashboard.php");
            exit; // Assure que le script s'arrête après la redirection
        }
    }
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tag'])) {
    $id_tag = intval($_POST['id_tag']); // Sécurise la donnée reçue
    $tag = new Tag(); // Instancie la classe Tag
    $tag->setId($id_tag); // Utilise le setter pour définir l'identifiant

    if ($tag->delete()) {
        header("Location: your_script.php"); // Recharge la page pour voir les changements
        exit;
    } else {
        echo "Erreur lors de la suppression du tag.";
    }
}



















// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $tagName = $_POST['updatedCategoryLabel'] ?? '';

//     if (empty($tagName)){
//         $errors[] = 'Vous devez Saisir une catégorie';
//     }
//     $category = new Category($tagName);
//     $category -> createCategory();
//     header("Location: ../public/adminDashboard.php");
// }