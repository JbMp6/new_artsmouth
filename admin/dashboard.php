<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}
?>

<h1>Dashboard</h1>
<nav>
    <a href="add-article.php">Ajouter un article</a> |
    <a href="delete-article.php">Supprimer un article</a> |
    <a href="view.php">Visualiser les articles</a> |
    <a href="logout.php">Déconnexion</a>
</nav>
