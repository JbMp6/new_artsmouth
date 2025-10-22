<?php
session_start();

// Hash du mot de passe "ArtsmouthAdmin1"
$hashedPassword = '$2y$10$2NR.aiu2YTpimYlmVgFMOO.79/c0gvGAhp2zUa0IV69UzRVIg8ziC'; // généré avec password_hash('ArtsmouthAdmin1', PASSWORD_DEFAULT);

$errors = [];

// Génération du token CSRF si inexistant
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = htmlspecialchars(trim($_POST['user'] ?? ''));
    $pass = trim($_POST['pass'] ?? '');
    $csrf = $_POST['csrf_token'] ?? '';

    // Vérification du token CSRF
    if ($csrf !== $_SESSION['csrf_token']) {
        $errors[] = "Formulaire invalide.";
    }

    // Vérification du login
    if (empty($errors) && $user === 'artsmouth' && password_verify($pass, $hashedPassword)) {
        session_regenerate_id(true); // sécurise la session
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else if (empty($errors)) {
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

        form h1 {
            color: #ffffff;
            font-size: 36px;
            font-weight: 300;
            margin-bottom: 40px;
            text-align: center;
        }

        form p {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 15px;
        }

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

        form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

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

            <?php foreach($errors as $e) echo "<p>$e</p>"; ?>

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="text" name="user" placeholder="Utilisateur" required>
            <input type="password" name="pass" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
