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

        /* Conteneur principal */
        .dashboard-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 50px 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            text-align: center;
        }

        h1 {
            color: #ffffff;
            font-size: 36px;
            font-weight: 300;
            margin-bottom: 40px;
        }

        /* Boutons du dashboard */
        nav {
            display: flex;
            flex-direction: column; /* empile verticalement */
            gap: 15px; /* espace entre les boutons */
        }

        nav a {
            display: block;
            padding: 12px 20px;
            background: #fff;
            color: #000;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-align: center;
        }

        nav a:hover {
            background-color: #ff0000;
            color: #fff;
            transform: scale(1.05);
        }

        /* Responsive */
        @media screen and (max-width: 450px) {
            .dashboard-container {
                width: 90%;
                padding: 40px 20px;
            }

            h1 {
                font-size: 28px;
                margin-bottom: 30px;
            }

            nav a {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard</h1>
        <nav>
            <a href="add-article.php">Ajouter un article</a>
            <a href="delete-article.php">Supprimer un article</a>
            <a href="view.php">Visualiser les articles</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </div>
</body>
</html>
