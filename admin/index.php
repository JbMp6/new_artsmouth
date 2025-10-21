<?php
session_start();
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    if($user === 'admin' && $pass === '1234') {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $errors[] = "Utilisateur ou mot de passe incorrect";
    }
}
?>

<form method="post">
    <h1>Login Admin</h1>
    <?php foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>
    <input type="text" name="user" placeholder="Utilisateur">
    <input type="password" name="pass" placeholder="Mot de passe">
    <button type="submit">Se connecter</button>
</form>