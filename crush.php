<?php
// Charger les articles depuis le JSON
$articlesFile = __DIR__ . '/data/articles.json';
$articles = [];

if (file_exists($articlesFile)) {
    $articles = json_decode(file_get_contents($articlesFile), true);
}

// Filtrer uniquement les articles de la page "crush"
$crushArticles = array_filter($articles, fn($a) => strtolower($a['page']) === 'crush');

// Trier par date décroissante
usort($crushArticles, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

// Vérifier si un article est demandé
$targetArticle = isset($_GET['article']) ? strtolower($_GET['article']) : null;

// Trouver l'index de la slide correspondante (si slider existe)
$targetIndex = 0;
if ($targetArticle) {
    foreach ($articlesSlider as $i => $a) { // $articlesSlider = tableau des articles pour le slider
        if (strtolower($a['titre']) === $targetArticle) {
            $targetIndex = $i;
            break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crush</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/slider_crush.css">
    <script src="assets/dots_slider.js"></script>

</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main>
        <div class="full_containter center_container">
            <div class="h1_container">
                <h1 class="h1_crush">/// CRUSH</h1>
            </div>
            <section class="crush-slider">
                <div class="slider-container">
                    <?php foreach ($crushArticles as $index => $article): ?>
                    <div class="slide <?= $index === 0 ? 'active' : '' ?>" 
                        style="background-image: url('<?= htmlspecialchars($article['image_bgrd']) ?>')">
                        <div class="full_container" style="height: 400px"></div>
                        <div class="overlay">
                            <div class="slider-dots">
                                <?php foreach ($crushArticles as $index2 => $article2): ?>
                                <span class="dot <?= $index2 === 0 ? 'active' : '' ?>" data-index="<?= $index2 ?>"></span>
                                <?php endforeach; ?>
                            </div>

                            <div class="content">
                                <div class="static_logo">
                                    <img src="assets/images/home_slider/logo.png" alt="Artsmouth Logo">
                                </div>
                                <div class="slide-content">
                                    <h2><?= htmlspecialchars($article['titre']) ?></h2>
                                    <p><?= nl2br(htmlspecialchars($article['desc'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
