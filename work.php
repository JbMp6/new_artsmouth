<?php
// Charger les articles depuis le JSON
$articlesFile = __DIR__ . '/data/articles.json';
$articles = [];

if (file_exists($articlesFile)) {
    $articles = json_decode(file_get_contents($articlesFile), true);
}

// Filtrer uniquement les articles de la page "work" et visibles
$workArticles = array_filter($articles, fn($a) => strtolower($a['page']) === 'work' && (isset($a['visible']) ? $a['visible'] : true));

// Trier par date décroissante
usort($workArticles, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

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
    <title>Work</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/slider_work.css">
    <link rel="stylesheet" href="assets/slider_work-mobile.css">
    <link rel="icon" type="image/jpg" href="assets\images\favicon.jpg">

</head>
<body id="work">
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main >
        <div class="full_containter center_container">
            <div class="h1_container">
                <h1 class="h1_work">/// WORK</h1>
            </div>
            <section class="work-slider">
                <div class="slider-container">
                    <?php foreach ($workArticles as $index => $article): ?>
                    <div class="slide <?= $index === 0 ? 'active' : '' ?>" 
                        style="background-image: url('<?= htmlspecialchars($article['image_bgrd']) ?>')">
                        <div class="full_container" style="height: 400px"></div>
                        <div class="overlay">
                            <div class="slider-dots">
                                <?php foreach ($workArticles as $index2 => $article2): ?>
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
            <section class="work_mobile">
                    <?php 
                    $keys = array_keys($workArticles);
                    $firstKey = $keys[0];
                    $lastKey  = end($keys);
                    ?>

                    <?php foreach ($workArticles as $index => $article): ?>
                        <div class="container_article" id="article-<?= $index ?>">
                            <div class="article" style="background-image: url('<?= htmlspecialchars($article['image_bgrd']) ?>')">
                                <div class="content_mobile">
                                    <h2><?= htmlspecialchars($article['titre']) ?></h2>
                                    <p><?= nl2br(htmlspecialchars($article['desc'])) ?></p>
                                </div>
                            </div>

                            <div class="logo_container_mobile">

                                <?php if ($index === $firstKey): ?>
                                    <a class="arrow_down" href="#next" id="next">
                                        <svg class="arrow-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 75px; height: auto;">
                                            <path d="M26.32,28.52c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.23c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.39,20.54c-.86.76-2.14.76-3,0l-23.38-20.54c-.94-.83-1.04-2.27-.21-3.21.44-.51,1.08-.77,1.71-.77Z"/>
                                            <path d="M26.32,42.13c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.22c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.38,20.55c-.86.76-2.14.76-3,0l-23.39-20.55c-.94-.83-1.04-2.27-.21-3.21.44-.52,1.08-.78,1.71-.78Z"/>
                                        </svg>
                                    </a>

                                <?php elseif ($index === $lastKey): ?>
                                    <a class="arrow_down up" href="#work">
                                        <svg class="arrow-svg up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 75px; height: auto;">
                                            <path d="M26.32,28.52c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.23c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.39,20.54c-.86.76-2.14.76-3,0l-23.38-20.54c-.94-.83-1.04-2.27-.21-3.21.44-.51,1.08-.77,1.71-.77Z"/>
                                            <path d="M26.32,42.13c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.22c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.38,20.55c-.86.76-2.14.76-3,0l-23.39-20.55c-.94-.83-1.04-2.27-.21-3.21.44-.52,1.08-.78,1.71-.78Z"/>
                                        </svg>
                                    </a>

                                <?php else: ?>
                                    <a class="arrow_down" href="#article-<?= $nextIndex ?>">
                                        <img src="assets/images/home_slider/logo.png" alt="Artsmouth Logo" class="logo_mobile_work">
                                    </a>

                                <?php endif; ?>

                            </div>
                        </div>

                    <?php endforeach; ?>

            </section>
        </div>
    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="assets/script.js"></script>
    <script src="assets/dots_slider.js"></script>
</body>
</html>
