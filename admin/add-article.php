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

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $featured_desc = trim($_POST['featured_desc'] ?? '');
    $image_bgrd = trim($_POST['image_bgrd'] ?? '');
    $featured_image = trim($_POST['featured_image'] ?? '');
    $page = trim($_POST['page'] ?? '');
    $video = trim($_POST['video'] ?? '');
    $date = $_POST['date'] ?? date('Y-m-d');

    if(!$titre) $errors[] = "Le titre est obligatoire";
    if(!$page) $errors[] = "La page est obligatoire";
    if(!$image_bgrd) $errors[] = "L'image de fond est obligatoire";

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

<h1>Ajouter un article</h1>

<?php foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>
<?php if($success) echo "<p style='color:green'>Article ajouté avec succès !</p>"; ?>

<form method="post">
    <input type="text" name="titre" placeholder="Titre">
    <input type="date" name="date" value="<?= date('Y-m-d') ?>">
    <textarea name="desc" placeholder="Description"></textarea>
    <textarea name="featured_desc" placeholder="Featured Description"></textarea>
    <input type="text" name="image_bgrd" placeholder="Image de fond (ex: admin/uploads/bg.jpg)">
    <input type="text" name="featured_image" placeholder="Featured image (ex: admin/uploads/featured.jpg)">
    <select name="page">
        <option value="">-- Choisir une page --</option>
        <option value="work">Work</option>
        <option value="crush">Crush</option>
        <option value="video">Video</option>
        <option value="video">Only Featured</option>
    </select>
    <input type="text" name="video" placeholder="Vidéo (facultatif)">
    <button type="submit">Créer</button>
</form>
