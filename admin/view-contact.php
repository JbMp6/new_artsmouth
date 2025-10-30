<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$jsonFile = dirname(__DIR__) . '/data/contacts.json';
$messages = [];
$successMessage = '';

if(file_exists($jsonFile)) {
    $raw = file_get_contents($jsonFile);
    $decoded = json_decode($raw, true);
    $messages = is_array($decoded) ? $decoded : [];
}

// Suppression d'un message
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $messages = array_filter($messages, fn($msg) => isset($msg['id']) && $msg['id'] !== $deleteId);

    $updated = json_encode(array_values($messages), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($updated !== false) {
        $bytes = @file_put_contents($jsonFile, $updated, LOCK_EX);
        if ($bytes === false) {
            $successMessage = "Erreur: impossible d'enregistrer les modifications.";
        } else {
            $successMessage = "Message supprimé avec succès !";
        }
    } else {
        $successMessage = "Erreur: encodage JSON invalide.";
    }

    // Recharger les messages après suppression
    $reload = json_decode(@file_get_contents($jsonFile), true);
    $messages = is_array($reload) ? $reload : [];
}
?>

<link rel="stylesheet" href="index.css">

<div class="form-container" style="width:80%; padding:30px 20px;">
    <a href="dashboard.php" style="background:#333; color:#fff; padding:10px 20px; border-radius:5px; text-decoration:none; display:inline-block; margin-bottom:20px;">← Retour au dashboard</a>
    <h1>Gestion des messages de contact</h1>
    
    <?php if($successMessage): ?>
        <div style="background:#00ff00; color:#000; padding:10px; border-radius:5px; margin-bottom:20px; font-weight:bold;"><?= $successMessage ?></div>
    <?php endif; ?>

    <?php if(empty($messages)): ?>
        <p>Aucun message trouvé.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse; color:#fff;">
            <thead>
                <tr style="background:#222;">
                    <th class="id">ID</th>
                    <th class="date">Date</th>
                    <th class="name">Nom</th>
                    <th class="email">Email</th>
                    <th class="message">Message</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($messages as $msg): ?>
                    <tr style="background:#333; text-align:center;">
                        <td class="id"><?= htmlspecialchars($msg['id']) ?></td>
                        <td class="date"><?= htmlspecialchars($msg['date']) ?></td>
                        <td class="name"><?= htmlspecialchars($msg['name']) ?></td>
                        <td class="email"><?= htmlspecialchars($msg['email']) ?></td>
                        <td class="message" style="text-align:left;"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                        <td class="actions">
                            <form method="post" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer ce message ?');">
                                <input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
                                <button type="submit" style="background:#cc0000; color:#fff; padding:5px 10px; border:none; border-radius:3px; font-size:12px; cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
    body {
        background: #111;
        font-family: Arial, sans-serif;
        color: #fff;
        margin:0;
        padding:0;
    }

    .id {
        width: 8%;
    }

    .date {
        width: 12%;
    }

    .name {
        width: 15%;
    }

    .email {
        width: 20%;
    }

    .message {
        width: 35%;
    }

    .actions {
        width: 10%;
    }

    .form-container {
        margin: 30px auto;
        background: rgba(0,0,0,0.8);
        border-radius: 10px;
    }

    table th, table td {
        padding: 10px;
        word-break: break-word;
    }

    table th {
        background-color: #222;
    }

    table tr:nth-child(even) {
        background-color: #2a2a2a;
    }

    table tr:hover {
        background-color: #444;
    }

    a {
        color: #ff0000;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    @media screen and (max-width: 900px) {
        .form-container {
            width: 95%;
            padding: 20px 10px;
        }

        table th, table td {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>
