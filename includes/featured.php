<?php
// Chemin vers le JSON
$jsonFile = __DIR__ . '/../data/articles.json';

// Vérifier que le fichier existe
if(!file_exists($jsonFile)){
    die("Fichier JSON introuvable : $jsonFile");
}

// Lire le JSON
$articles = json_decode(file_get_contents($jsonFile), true);
if(!$articles){
    die("Impossible de lire les articles ou JSON vide");
}

// Ici, tu peux filtrer les articles si nécessaire, sinon utilise directement $articles
$articles_non_home = $articles; // ajouter cette ligne

if(empty($articles_non_home)){
    die("Aucun article à afficher");
}

// Trier par date décroissante
usort($articles_non_home, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

// Prendre le dernier article
$featured = reset($articles_non_home);
?>
<link rel="stylesheet" href="assets/featured.css">
<section class="full_container featured_article">
    <img src="<?= htmlspecialchars($featured['featured_image']) ?>" alt="<?= htmlspecialchars($featured['titre']) ?>">
    <div class="featured_content">
        <h2><?= htmlspecialchars($featured['titre']) ?></h2>
        <p><?= htmlspecialchars($featured['featured_desc']) ?></p>
    </div>
</section>
