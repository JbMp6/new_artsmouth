<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$jsonFile = '../data/contacts.json';
$messages = [];

if(file_exists($jsonFile)) {
    $messages = json_decode(file_get_contents($jsonFile), true);
}
?>

<link rel="stylesheet" href="index.css">

<div class="form-container" style="width:80%; padding:30px 20px;">
    <h1>Liste des messages de contact</h1>

    <?php if(empty($messages)): ?>
        <p>Aucun message trouvé.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse; color:#fff;">
            <thead>
                <tr style="background:#222;">
                    <th>ID</th>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($messages as $msg): ?>
                    <tr style="background:#333; text-align:center;">
                        <td><?= htmlspecialchars($msg['id']) ?></td>
                        <td><?= htmlspecialchars($msg['date']) ?></td>
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td style="text-align:left;"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
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
