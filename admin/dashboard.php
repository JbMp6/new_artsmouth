<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
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

        .dashboard-container {
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
            text-align: center;
        }

        nav {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }

        nav a {
            display: block;
            width: 100%;
            background: #ffffff;
            color: #000000;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
            border-radius: 6px;
            padding: 12px 24px;
            text-align: center;
            transition: background 0.3s;
        }

        nav a:hover {
            background: #ff0000;
            color: #ffffff;
        }

        @media screen and (max-width: 450px) {
            .dashboard-container {
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
    <div class="dashboard-container">
        <h1>Dashboard</h1>
        <nav>
            <a href="add-article.php">Ajouter un article</a>
            <a href="view.php">Gérer les articles</a>
            <a href="view-contact.php">Voir les messages de contact</a>
            <a href="manage-popup.php">Gérer le popup</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </div>
</body>
</html>
