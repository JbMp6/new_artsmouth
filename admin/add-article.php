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

if(file_exists($jsonFile)) {
    $articles = json_decode(file_get_contents($jsonFile), true);
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
    $visible = isset($_POST['visible']) ? (bool)$_POST['visible'] : true;

    // Validation des champs obligatoires
    if(!$titre) $errors[] = "Le titre est obligatoire";
    if(!$page) $errors[] = "La page est obligatoire";

    // Gestion de l'upload de l'image de fond
    $image_bgrd = '';
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
    $featured_image = '';
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
    
    // Ajouter 'admin/' au chemin des images pour le JSON
    if(!empty($image_bgrd)) {
        $image_bgrd_json = 'admin/' . $image_bgrd;
    } else {
        $image_bgrd_json = '';
    }
    if(!empty($featured_image)) {
        $featured_image_json = 'admin/' . $featured_image;
    } else {
        $featured_image_json = '';
    }

    if(empty($errors)) {
        $id = count($articles) ? max(array_column($articles, 'id')) + 1 : 1;

        $newArticle = [
            'id' => $id,
            'date' => $date,
            'titre' => $titre,
            'desc' => $desc,
            'featured_desc' => $featured_desc,
            'image_bgrd' => $image_bgrd_json,
            'featured_image' => $featured_image_json,
            'page' => $page,
            'video' => $video,
            'visible' => $visible
        ];

        $articles[] = $newArticle;
        file_put_contents($jsonFile, json_encode($articles, JSON_PRETTY_PRINT));
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
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
        <h1>Ajouter un article</h1>

        <?php foreach($errors as $e) echo "<p class='error'>$e</p>"; ?>
        <?php if($success) echo "<p class='success'>Article ajouté avec succès !</p>"; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre" required>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>">
            <textarea name="desc" placeholder="Description"></textarea>
            <textarea name="featured_desc" placeholder="Featured Description"></textarea>
            <label for="image_bgrd">Image de fond</label>
            <input type="file" name="image_bgrd" accept="image/*">
            <label for="featured_image">Image featured</label>
            <input type="file" name="featured_image" accept="image/*">
            <select name="page" required>
                <option value="">-- Choisir une page --</option>
                <option value="work">Work</option>
                <option value="crush">Crush</option>
                <option value="video">Video</option>
                <option value="featured">Only Featured</option>
            </select>
            <input type="text" name="video" placeholder="Vidéo (facultatif)">
            <div class="checkbox-wrapper">
                <input type="checkbox" name="visible" id="visible" checked>
                <label for="visible" style="margin-bottom: 0;">Article visible</label>
            </div>
            <button type="submit">Créer</button>
        </form>

        <div class="back-link">
            <a href="dashboard.php">← Retour au dashboard</a>
        </div>
    </div>
</body>
</html>
