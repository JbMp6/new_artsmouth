<?php
// Chemin vers le JSON
$jsonFile = __DIR__ . '/../data/articles.json';

// Vérifier que le fichier existe
if (!file_exists($jsonFile)) {
    die("Fichier JSON introuvable : $jsonFile");
}

// Lire le JSON
$articles = json_decode(file_get_contents($jsonFile), true);
if (!$articles) {
    die("Impossible de lire les articles ou JSON vide");
}

// Filtrer les articles si nécessaire (ici tous)
$articles_non_home = $articles;

if (empty($articles_non_home)) {
    die("Aucun article à afficher");
}

// Trier par date décroissante
usort($articles_non_home, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

// Prendre le dernier article
$featured = reset($articles_non_home);

// Déterminer la page cible selon le type de l'article
switch (strtolower($featured['page'])) {
    case 'video':
        $targetPage = 'video.php';
        break;
    case 'work':
        $targetPage = 'work.php';
        break;
    case 'crush':
        $targetPage = 'crush.php';
        break;
    default:
        $targetPage = 'video.php'; // fallback
}
?>
<link rel="stylesheet" href="assets/featured.css">

<section class="full_container featured_article">
    <img src="<?= htmlspecialchars($featured['featured_image']) ?>" alt="<?= htmlspecialchars($featured['titre']) ?>">
    <div class="featured_content">
        <h2><?= htmlspecialchars($featured['titre']) ?></h2>
        <p><?= htmlspecialchars($featured['featured_desc']) ?></p>
        <div class="center_container">
            <a href="<?= $targetPage ?>?article=<?= urlencode($featured['titre']) ?>" class="btn_featured">
                Voir <?= htmlspecialchars($featured['page']) ?>
            </a>
        </div>
    </div>
</section>
