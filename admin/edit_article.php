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

$uploadDir = 'admin/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $featured_desc = trim($_POST['featured_desc'] ?? '');
    $page = trim($_POST['page'] ?? '');
    $video = trim($_POST['video'] ?? '');
    $date = $_POST['date'] ?? date('Y-m-d');

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
            $image_bgrd = $targetFile;
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
            $featured_image = $targetFile;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image featured";
        }
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
            'video' => $video
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

        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 50px 40px;
            border-radius: 10px;
            width: 500px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #ffffff;
            font-size: 36px;
            font-weight: 300;
            margin-bottom: 40px;
        }

        p {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error {
            color: #ff4d4d;
        }

        .success {
            color: #00ff00;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input, textarea, select {
            width: 100%;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
            resize: none;
            transition: all 0.3s ease;
            box-sizing: border-box;
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

        input:focus, textarea:focus, select:focus {
            border-color: #ff0000;
            box-shadow: 0 0 5px #ff0000;
        }

        ::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        button {
            width: 100%;
            background: #fff;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #ff0000;
            color: #fff;
            transform: scale(1.05);
        }

        .back-btn {
            background: #333;
            color: #fff;
            width: auto;
            padding: 10px 20px;
            margin-bottom: 20px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .back-btn:hover {
            background: #555;
            transform: scale(1.05);
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
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #fff;
            padding: 12px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background: rgba(255, 255, 255, 0.2);
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
        <a href="dashboard.php" class="back-btn">← Retour au dashboard</a>
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
            
            <button type="submit">Modifier l'article</button>
        </form>
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
