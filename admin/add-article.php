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
    $image_bgrd = '';
    if(isset($_FILES['image_bgrd']) && $_FILES['image_bgrd']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image_bgrd']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['image_bgrd']['name']);
        $targetFile = $uploadDir . $fileName;
        if(move_uploaded_file($tmpName, $targetFile)) {
            $image_bgrd = $targetFile;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image de fond";
        }
    } else {
        $errors[] = "L'image de fond est obligatoire";
    }

    // Gestion de l'upload de l'image featured
    $featured_image = '';
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
        $id = count($articles) ? max(array_column($articles, 'id')) + 1 : 1;

        $newArticle = [
            'id' => $id,
            'date' => $date,
            'titre' => $titre,
            'desc' => $desc,
            'featured_desc' => $featured_desc,
            'image_bgrd' => $image_bgrd,
            'featured_image' => $featured_image,
            'page' => $page,
            'video' => $video
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
        }

        button:hover {
            background-color: #ff0000;
            color: #fff;
            transform: scale(1.05);
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
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
            <textarea name="desc" placeholder="Description"></textarea>
            <textarea name="featured_desc" placeholder="Featured Description"></textarea>
            <input type="file" name="image_bgrd" accept="image/*" required>
            <input type="file" name="featured_image" accept="image/*">
            <select name="page" required>
                <option value="">-- Choisir une page --</option>
                <option value="work">Work</option>
                <option value="crush">Crush</option>
                <option value="video">Video</option>
                <option value="featured">Only Featured</option>
            </select>
            <input type="text" name="video" placeholder="Vidéo (facultatif)">
            <button type="submit">Créer</button>
        </form>
    </div>
</body>
</html>
