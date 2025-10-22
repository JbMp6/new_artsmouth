<?php
session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    // Vérification simple du login
    if ($user === 'admin' && $pass === '1234') {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php'); // redirection vers la page admin
        exit;
    } else {
        $errors[] = "Utilisateur ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        /* Corps de la page */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1a1a1a, #333);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Conteneur principal du formulaire */
        .form-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 50px 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Titre du formulaire */
        form h1 {
            color: #ffffff;
            font-size: 36px;
            font-weight: 300;
            margin-bottom: 40px;
            text-align: center;
        }

        /* Messages d'erreur */
        form p {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        /* Styles des champs */
        form input {
            width: 100%;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        form input:focus {
            border-color: #ff0000;
            box-shadow: 0 0 5px #ff0000;
        }

        /* Placeholder */
        form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Bouton */
        form button {
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

        form button:hover {
            background-color: #ff0000;
            color: #fff;
            transform: scale(1.05);
        }

        /* Responsive */
        @media screen and (max-width: 450px) {
            .form-container {
                width: 90%;
                padding: 40px 20px;
            }

            form h1 {
                font-size: 28px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h1>Login Admin</h1>

            <?php 
            foreach($errors as $e) {
                echo "<p>$e</p>";
            } 
            ?>

            <input type="text" name="user" placeholder="Utilisateur" required>
            <input type="password" name="pass" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
