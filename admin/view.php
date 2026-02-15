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
    // Recharger les articles après suppression
    $articles = json_decode(file_get_contents($jsonFile), true);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des articles</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: "Roboto", sans-serif;
            background-color: #000000;
            color: #ffffff;
            min-height: 100vh;
            padding: 40px 2%;
        }

        .page-container {
            max-width: 100%;
            margin: 0 auto;
        }

        h1 {
            color: #ffffff;
            font-size: 45px;
            font-weight: 300;
            margin-bottom: 30px;
            text-align: left;
        }

        .success {
            background: #00ff00;
            color: #000;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            font-weight: bold;
            text-align: center;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: transparent;
        }

        table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        table th {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-weight: bold;
            padding: 15px 12px;
            text-align: left;
            border-bottom: 2px solid #ffffff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table th:first-child {
            border-top-left-radius: 6px;
        }

        table th:last-child {
            border-top-right-radius: 6px;
        }

        table td {
            padding: 15px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 14px;
            vertical-align: top;
            word-wrap: break-word;
            max-width: 200px;
        }

        table td.cell-actions {
            vertical-align: middle;
            padding: 0;
            height: 100%;
        }
        
        table td.cell-actions > div {
            display: flex;
            flex-direction: column;
            gap: 8px;
            justify-content: center;
            align-items: center;
            min-height: 100%;
            padding: 15px 12px;
        }

        table tbody tr {
            transition: background-color 0.2s ease;
        }

        table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        .cell-id {
            text-align: center;
            font-weight: bold;
            width: 60px;
        }

        .cell-date {
            width: 120px;
            text-align: center;
        }

        .cell-titre {
            font-weight: 500;
            min-width: 150px;
        }

        .cell-desc {
            max-width: 250px;
            line-height: 1.5;
        }

        .cell-featured-desc {
            max-width: 250px;
            line-height: 1.5;
        }

        .cell-page {
            text-align: center;
            text-transform: capitalize;
            width: 100px;
        }

        .cell-visible {
            text-align: center;
            width: 80px;
            font-weight: bold;
        }

        .cell-visible.true {
            color: #00ff00;
        }

        .cell-visible.false {
            color: #ff0000;
        }

        .cell-actions {
            text-align: center;
            width: 120px;
            vertical-align: middle;
        }

        .cell-actions a,
        .cell-actions button {
            display: inline-block;
            background: #ffffff;
            color: #000000;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            width: 100px;
            text-align: center;
            box-sizing: border-box;
        }

        .cell-actions a:hover,
        .cell-actions button:hover {
            background: #ff0000;
            color: #ffffff;
        }

        .cell-link {
            color: #ffffff;
            text-decoration: underline;
            transition: color 0.3s;
        }

        .cell-link:hover {
            color: #ff0000;
        }

        .back-link {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 100;
        }

        .back-link a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            background: #ffffff;
            color: #000000;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
            display: inline-block;
        }

        .back-link a:hover {
            background: #ff0000;
            color: #ffffff;
        }

        @media screen and (max-width: 1200px) {
            body {
                padding: 30px 1%;
            }

            h1 {
                font-size: 36px;
                margin-bottom: 20px;
            }

            table th,
            table td {
                padding: 12px 8px;
                font-size: 12px;
            }

            .cell-desc,
            .cell-featured-desc {
                max-width: 150px;
            }
        }

        @media screen and (max-width: 768px) {
            body {
                padding: 20px 1%;
            }

            h1 {
                font-size: 28px;
            }

            table {
                font-size: 11px;
            }

            table th,
            table td {
                padding: 8px 5px;
            }

            .cell-actions a,
            .cell-actions button {
                padding: 6px 10px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h1>Gestion des articles</h1>
        
        <?php if($successMessage): ?>
            <div class="success"><?= $successMessage ?></div>
        <?php endif; ?>

        <?php if(empty($articles)): ?>
            <div class="empty-message">Aucun article trouvé.</div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th class="cell-id">ID</th>
                            <th class="cell-date">Date</th>
                            <th class="cell-titre">Titre</th>
                            <th class="cell-desc">Description</th>
                            <th class="cell-featured-desc">Featured Desc</th>
                            <th>Image de fond</th>
                            <th>Featured Image</th>
                            <th class="cell-page">Page</th>
                            <th>Vidéo</th>
                            <th class="cell-visible">Visible</th>
                            <th class="cell-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($articles as $a): ?>
                            <tr>
                                <td class="cell-id"><?= htmlspecialchars($a['id']) ?></td>
                                <td class="cell-date"><?= htmlspecialchars($a['date']) ?></td>
                                <td class="cell-titre"><?= htmlspecialchars($a['titre']) ?></td>
                                <td class="cell-desc"><?= nl2br(htmlspecialchars($a['desc'])) ?></td>
                                <td class="cell-featured-desc"><?= nl2br(htmlspecialchars($a['featured_desc'])) ?></td>
                                <td>
                                    <?php if($a['image_bgrd']): ?>
                                        <a href="<?= htmlspecialchars('../' . $a['image_bgrd']) ?>" target="_blank" class="cell-link">Voir</a>
                                    <?php else: ?>
                                        <span style="color: rgba(255,255,255,0.5);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($a['featured_image']): ?>
                                        <a href="<?= htmlspecialchars('../' . $a['featured_image']) ?>" target="_blank" class="cell-link">Voir</a>
                                    <?php else: ?>
                                        <span style="color: rgba(255,255,255,0.5);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="cell-page"><?= htmlspecialchars($a['page']) ?></td>
                                <td>
                                    <?php if($a['video']): ?>
                                        <a href="<?= htmlspecialchars($a['video']) ?>" target="_blank" class="cell-link">Voir</a>
                                    <?php else: ?>
                                        <span style="color: rgba(255,255,255,0.5);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="cell-visible <?= (isset($a['visible']) && $a['visible']) ? 'true' : 'false' ?>">
                                    <?= (isset($a['visible']) && $a['visible']) ? 'vrai' : 'faux' ?>
                                </td>
                                <td class="cell-actions">
                                    <div>
                                        <a href="edit_article.php?id=<?= $a['id'] ?>">Modifier</a>
                                        <form method="post" style="display:inline; margin:0;" onsubmit="return confirm('Voulez-vous vraiment supprimer cet article ?');">
                                            <input type="hidden" name="delete_id" value="<?= $a['id'] ?>">
                                            <button type="submit">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="back-link">
        <a href="dashboard.php">← Retour au dashboard</a>
    </div>
</body>
</html>
