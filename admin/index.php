<?php
session_start();
$hashedPassword = '$2y$10$2NR.aiu2YTpimYlmVgFMOO.79/c0gvGAhp2zUa0IV69UzRVIg8ziC';
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
        session_regenerate_id(true);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
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
        
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        
        form h1 {
            color: #ffffff;
            font-size: 45px;
            font-weight: 300;
            margin-bottom: 40px;
            text-align: center;
        }
        
        form p {
            color: #ff0000;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            outline: none;
            font-size: 14px;
        }
        
        form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        form button {
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
        
        form button:hover {
            background: #ff0000;
            color: #ffffff;
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