<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = false;
$jsonFile = '../data/articles.json';
$articles = [];
$article = null;

// Récupérer l'ID de l'article à modifier
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if(file_exists($jsonFile)) {
    $articles = json_decode(file_get_contents($jsonFile), true);
    
    // Trouver l'article à modifier
    foreach($articles as $key => $art) {
        if($art['id'] == $articleId) {
            $article = $art;
            $articleIndex = $key;
            break;
        }
    }
}

// Si l'article n'existe pas, rediriger
if(!$article) {
    header('Location: dashboard.php');
    exit;
}

// Utiliser le chemin absolu du dossier uploads dans admin
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
// Pour le JSON, on utilise le chemin relatif depuis la racine
$uploadDirForJson = 'uploads/';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $featured_desc = trim($_POST['featured_desc'] ?? '');
    $page = trim($_POST['page'] ?? '');
    $video = trim($_POST['video'] ?? '');
    $date = $_POST['date'] ?? date('Y-m-d');
    $visible = isset($_POST['visible']) ? true : false;

    // Validation des champs obligatoires
    if(!$titre) $errors[] = "Le titre est obligatoire";
    if(!$page) $errors[] = "La page est obligatoire";

    // Gestion de l'upload de l'image de fond
    $image_bgrd = $article['image_bgrd']; // Garder l'image existante par défaut
    if(isset($_FILES['image_bgrd']) && $_FILES['image_bgrd']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image_bgrd']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['image_bgrd']['name']);
        $targetFile = $uploadDir . $fileName;
        if(move_uploaded_file($tmpName, $targetFile)) {
            // Utiliser le chemin relatif pour le JSON
            $image_bgrd = $uploadDirForJson . $fileName;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image de fond";
        }
    }

    // Gestion de l'upload de l'image featured
    $featured_image = $article['featured_image']; // Garder l'image existante par défaut
    if(isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['featured_image']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['featured_image']['name']);
        $targetFile = $uploadDir . $fileName;
        if(move_uploaded_file($tmpName, $targetFile)) {
            // Utiliser le chemin relatif pour le JSON
            $featured_image = $uploadDirForJson . $fileName;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image featured";
        }
    }
    
    // Ajouter 'admin/' au chemin des nouvelles images pour le JSON
    if(isset($_FILES['image_bgrd']) && $_FILES['image_bgrd']['error'] === UPLOAD_ERR_OK && !empty($image_bgrd) && strpos($image_bgrd, 'admin/') === false) {
        $image_bgrd = 'admin/' . $image_bgrd;
    }
    if(isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK && !empty($featured_image) && strpos($featured_image, 'admin/') === false) {
        $featured_image = 'admin/' . $featured_image;
    }

    if(empty($errors)) {
        // Mettre à jour l'article
        $articles[$articleIndex] = [
            'id' => $articleId,
            'date' => $date,
            'titre' => $titre,
            'desc' => $desc,
            'featured_desc' => $featured_desc,
            'image_bgrd' => $image_bgrd,
            'featured_image' => $featured_image,
            'page' => $page,
            'video' => $video,
            'visible' => $visible
        ];

        file_put_contents($jsonFile, json_encode($articles, JSON_PRETTY_PRINT));
        $success = true;
        
        // Mettre à jour l'article pour l'affichage
        $article = $articles[$articleIndex];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un article</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: "Roboto", sans-serif;
            background-color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            width: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 40px;
        }

        h1 {
            color: #ffffff;
            font-size: 45px;
            font-weight: 300;
            margin-bottom: 40px;
        }

        p {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error {
            color: #ff0000;
        }

        .success {
            color: #00ff00;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        input, textarea, select {
            width: 100%;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            outline: none;
            font-size: 14px;
            resize: none;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
            cursor: pointer;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 15px;
        }

        textarea {
            height: 150px;
        }
        
        label {
            color: #fff;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            line-height: normal;
        }

        ::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        button {
            width: 100%;
            background: #ffffff;
            color: #000000;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
            border-radius: 6px;
            padding: 12px 24px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #ff0000;
            color: #ffffff;
        }

        .back-link {
            margin-top: 20px;
            text-align: center;
        }

        .back-link a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            background: #ffffff;
            color: #000000;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
            display: inline-block;
        }

        .back-link a:hover {
            background: #ff0000;
            color: #ffffff;
        }

        .current-image {
            color: #ccc;
            font-size: 12px;
            margin-bottom: 10px;
            font-style: italic;
        }

        .file-input-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 15px;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: block;
            background: transparent;
            border: 1px solid #fff;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            color: #fff;
        }

        .file-input-label:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        @media screen and (max-width: 450px) {
            .form-container {
                width: 90%;
                padding: 40px 20px;
            }

            h1 {
                font-size: 28px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Modifier l'article</h1>

        <?php foreach($errors as $e) echo "<p class='error'>$e</p>"; ?>
        <?php if($success) echo "<p class='success'>Article modifié avec succès !</p>"; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
            
            <input type="date" name="date" value="<?= $article['date'] ?>" required>
            
            <textarea name="desc" placeholder="Description"><?= htmlspecialchars($article['desc']) ?></textarea>
            
            <textarea name="featured_desc" placeholder="Featured Description"><?= htmlspecialchars($article['featured_desc']) ?></textarea>
            
            <label for="image_bgrd">Image de fond</label>
            <?php if($article['image_bgrd']): ?>
                <div class="current-image">Image actuelle: <?= basename($article['image_bgrd']) ?></div>
            <?php endif; ?>
            <div class="file-input-wrapper">
                <input type="file" name="image_bgrd" accept="image/*" id="image_bgrd">
                <label for="image_bgrd" class="file-input-label">Choisir une nouvelle image de fond</label>
            </div>
            
            <label for="featured_image">Image featured</label>
            <?php if($article['featured_image']): ?>
                <div class="current-image">Image actuelle: <?= basename($article['featured_image']) ?></div>
            <?php endif; ?>
            <div class="file-input-wrapper">
                <input type="file" name="featured_image" accept="image/*" id="featured_image">
                <label for="featured_image" class="file-input-label">Choisir une nouvelle image featured</label>
            </div>
            
            <select name="page" required>
                <option value="">-- Choisir une page --</option>
                <option value="work" <?= $article['page'] == 'work' ? 'selected' : '' ?>>Work</option>
                <option value="crush" <?= $article['page'] == 'crush' ? 'selected' : '' ?>>Crush</option>
                <option value="video" <?= $article['page'] == 'video' ? 'selected' : '' ?>>Video</option>
                <option value="featured" <?= $article['page'] == 'featured' ? 'selected' : '' ?>>Only Featured</option>
            </select>
            
            <input type="text" name="video" placeholder="Vidéo (facultatif)" value="<?= htmlspecialchars($article['video']) ?>">
            
            <div class="checkbox-wrapper">
                <input type="checkbox" name="visible" id="visible" <?= (isset($article['visible']) && $article['visible']) ? 'checked' : '' ?>>
                <label for="visible" style="margin-bottom: 0;">Article visible</label>
            </div>
            
            <button type="submit">Modifier l'article</button>
        </form>

        <div class="back-link">
            <a href="dashboard.php">← Retour au dashboard</a>
        </div>
    </div>

    <script>
        // Améliorer l'expérience utilisateur pour les uploads de fichiers
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const label = this.nextElementSibling;
                if (this.files.length > 0) {
                    label.textContent = 'Fichier sélectionné: ' + this.files[0].name;
                    label.style.color = '#00ff00';
                } else {
                    label.textContent = label.textContent.replace(/Fichier sélectionné: .+/, 'Choisir un fichier');
                    label.style.color = '#fff';
                }
            });
        });
    </script>
</body>
</html>
