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

<link rel="stylesheet" href="index.css">

<div class="form-container" style="width:80%; padding:30px 20px;">
    <a href="dashboard.php" style="background:#333; color:#fff; padding:10px 20px; border-radius:5px; text-decoration:none; display:inline-block; margin-bottom:20px;">← Retour au dashboard</a>
    <h1>Gestion des articles</h1>
    
    <?php if($successMessage): ?>
        <div style="background:#00ff00; color:#000; padding:10px; border-radius:5px; margin-bottom:20px; font-weight:bold;"><?= $successMessage ?></div>
    <?php endif; ?>

    <?php if(empty($articles)): ?>
        <p>Aucun article trouvé.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse; color:#fff;">
            <thead>
                <tr style="background:#222;">
                    <th>ID</th>
                    <th>Date</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Featured Desc</th>
                    <th>Image de fond</th>
                    <th>Featured Image</th>
                    <th>Page</th>
                    <th>Vidéo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($articles as $a): ?>
                    <tr style="background:#333; text-align:center;">
                        <td><?= htmlspecialchars($a['id']) ?></td>
                        <td><?= htmlspecialchars($a['date']) ?></td>
                        <td><?= htmlspecialchars($a['titre']) ?></td>
                        <td><?= nl2br(htmlspecialchars($a['desc'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($a['featured_desc'])) ?></td>
                        <td>
                            <?php if($a['image_bgrd']): ?>
                                <a href="<?= htmlspecialchars('uploads/' . basename($a['image_bgrd'])) ?>" target="_blank">Lien</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($a['featured_image']): ?>
                                <a href="<?= htmlspecialchars('uploads/' . basename($a['featured_image'])) ?>" target="_blank">Lien</a>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($a['page']) ?></td>
                        <td>
                            <?php if($a['video']): ?>
                                <a href="<?= htmlspecialchars($a['video']) ?>" target="_blank">Voir vidéo</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_article.php?id=<?= $a['id'] ?>" style="background:#ff0000; color:#fff; padding:5px 10px; border-radius:3px; text-decoration:none; font-size:12px; margin-right:5px;">Modifier</a>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cet article ?');">
                                <input type="hidden" name="delete_id" value="<?= $a['id'] ?>">
                                <button type="submit" style="background:#cc0000; color:#fff; padding:5px 10px; border:none; border-radius:3px; font-size:12px; cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
    body {
        background: #111;
        font-family: Arial, sans-serif;
        color: #fff;
        margin:0;
        padding:0;
    }

    .form-container {
        margin: 30px auto;
        background: rgba(0,0,0,0.8);
        border-radius: 10px;
    }

    table th, table td {
        padding: 10px;
        word-break: break-word;
    }

    table th {
        background-color: #222;
    }

    table tr:nth-child(even) {
        background-color: #2a2a2a;
    }

    table tr:hover {
        background-color: #444;
    }

    a {
        color: #ff0000;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    @media screen and (max-width: 900px) {
        .form-container {
            width: 95%;
            padding: 20px 10px;
        }

        table th, table td {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>
