<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = false;
$popupPath = '../assets/images/popup.jpg';

// Vérifier si le fichier popup existe
$popupExists = file_exists($popupPath);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gestion de l'upload de la nouvelle image popup
    if(isset($_FILES['popup_image']) && $_FILES['popup_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['popup_image']['tmp_name'];
        $fileType = strtolower(pathinfo($_FILES['popup_image']['name'], PATHINFO_EXTENSION));
        
        // Vérifier que c'est une image
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if(!in_array($fileType, $allowedTypes)) {
            $errors[] = "Le fichier doit être une image (JPG, PNG, GIF ou WEBP)";
        } else {
            // Supprimer l'ancienne image si elle existe
            if($popupExists) {
                unlink($popupPath);
            }
            
            // Créer le dossier s'il n'existe pas
            $popupDir = dirname($popupPath);
            if(!is_dir($popupDir)) {
                mkdir($popupDir, 0755, true);
            }
            
            // Convertir l'image en JPG si nécessaire et la sauvegarder
            if($fileType === 'jpg' || $fileType === 'jpeg') {
                // Copier directement si c'est déjà un JPG
                if(move_uploaded_file($tmpName, $popupPath)) {
                    $success = true;
                } else {
                    $errors[] = "Erreur lors de l'upload de l'image";
                }
            } else {
                // Convertir en JPG si l'extension GD est disponible
                if(function_exists('imagecreatefrompng') && function_exists('imagejpeg')) {
                    $image = null;
                    switch($fileType) {
                        case 'png':
                            $image = @imagecreatefrompng($tmpName);
                            break;
                        case 'gif':
                            $image = @imagecreatefromgif($tmpName);
                            break;
                        case 'webp':
                            if(function_exists('imagecreatefromwebp')) {
                                $image = @imagecreatefromwebp($tmpName);
                            }
                            break;
                    }
                    
                    if($image) {
                        // Convertir en JPG
                        if(imagejpeg($image, $popupPath, 90)) {
                            imagedestroy($image);
                            $success = true;
                        } else {
                            $errors[] = "Erreur lors de la conversion de l'image";
                            imagedestroy($image);
                        }
                    } else {
                        $errors[] = "Impossible de lire l'image. Veuillez utiliser un fichier JPG.";
                    }
                } else {
                    // Si GD n'est pas disponible, demander un fichier JPG
                    $errors[] = "L'extension GD n'est pas disponible. Veuillez utiliser un fichier JPG.";
                }
            }
        }
    } else {
        $errors[] = "Veuillez sélectionner une image";
    }
}

// Vérifier à nouveau si le fichier existe après l'upload
$popupExists = file_exists($popupPath);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer le Popup</title>
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
            width: 600px;
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
            text-align: center;
        }

        .error {
            color: #ff0000;
        }

        .success {
            color: #00ff00;
        }

        .current-image {
            margin-bottom: 30px;
            text-align: center;
        }

        .current-image img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .current-image p {
            color: #ffffff;
            font-size: 14px;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            color: #fff;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }

        input[type="file"] {
            width: 100%;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            outline: none;
            font-size: 14px;
            box-sizing: border-box;
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            background: #ffffff;
            color: #000000;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            font-weight: bold;
            transition: background 0.3s;
        }

        input[type="file"]::file-selector-button:hover {
            background: #ff0000;
            color: #ffffff;
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

        @media screen and (max-width: 650px) {
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
        <h1>Gérer le Popup</h1>

        <?php foreach($errors as $e) echo "<p class='error'>$e</p>"; ?>
        <?php if($success) echo "<p class='success'>Image popup mise à jour avec succès !</p>"; ?>

        <?php if($popupExists): ?>
        <div class="current-image">
            <p>Image actuelle :</p>
            <img src="../assets/images/popup.jpg?t=<?= time() ?>" alt="Popup actuel">
        </div>
        <?php else: ?>
        <div class="current-image">
            <p class="error">Aucune image popup actuellement</p>
        </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="popup_image">Nouvelle image popup (JPG, PNG, GIF ou WEBP)</label>
            <input type="file" name="popup_image" accept="image/*" required>
            <button type="submit">Remplacer l'image</button>
        </form>

        <div class="back-link">
            <a href="dashboard.php">← Retour au dashboard</a>
        </div>
    </div>
</body>
</html>

