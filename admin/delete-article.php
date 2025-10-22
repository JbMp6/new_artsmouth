<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$jsonFile = '../data/articles.json';
$articles = [];
$successMessage = '';

if(file_exists($jsonFile)) {
    $articles = json_decode(file_get_contents($jsonFile), true);
}

// Suppression d'un article
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    $articles = array_filter($articles, fn($a) => $a['id'] !== $deleteId);
    file_put_contents($jsonFile, json_encode(array_values($articles), JSON_PRETTY_PRINT));
    $successMessage = "Article supprimé avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un article</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1a1a1a, #333);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dashboard-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 50px 40px;
            border-radius: 10px;
            width: 500px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            text-align: center;
        }

        h1 {
            color: #ffffff;
            font-size: 36px;
            font-weight: 300;
            margin-bottom: 40px;
        }

        .success {
            color: #00ff00;
            margin-bottom: 20px;
        }

        .article-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #222;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .article-item span {
            color: #fff;
            font-weight: bold;
        }

        .article-item form button {
            padding: 8px 15px;
            background: #ff0000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .article-item form button:hover {
            background: #fff;
            color: #ff0000;
            transform: scale(1.05);
        }

        @media screen and (max-width: 450px) {
            .dashboard-container {
                width: 90%;
                padding: 40px 20px;
            }

            h1 {
                font-size: 28px;
                margin-bottom: 30px;
            }

            .article-item {
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }

            .article-item form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Supprimer un article</h1>

        <?php if($successMessage): ?>
            <div class="success"><?= $successMessage ?></div>
        <?php endif; ?>

        <?php if(empty($articles)): ?>
            <p style="color:#fff;">Aucun article disponible.</p>
        <?php else: ?>
            <?php foreach($articles as $a): ?>
                <div class="article-item">
                    <span><?= htmlspecialchars($a['titre']) ?></span>
                    <form method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer cet article ?');">
                        <input type="hidden" name="delete_id" value="<?= $a['id'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
