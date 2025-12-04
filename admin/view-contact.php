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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des messages de contact</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: "Roboto", sans-serif;
            background-color: #000000;
            color: #ffffff;
            min-height: 100vh;
            padding: 40px 2%;
        }

        .page-container {
            max-width: 100%;
            margin: 0 auto;
        }

        h1 {
            color: #ffffff;
            font-size: 45px;
            font-weight: 300;
            margin-bottom: 30px;
            text-align: left;
        }

        .success {
            background: #00ff00;
            color: #000;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            font-weight: bold;
            text-align: center;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: transparent;
        }

        table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        table th {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-weight: bold;
            padding: 15px 12px;
            text-align: left;
            border-bottom: 2px solid #ffffff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table th:first-child {
            border-top-left-radius: 6px;
        }

        table th:last-child {
            border-top-right-radius: 6px;
        }

        table td {
            padding: 15px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 14px;
            vertical-align: top;
            word-wrap: break-word;
        }

        table tbody tr {
            transition: background-color 0.2s ease;
        }

        table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        .cell-id {
            text-align: center;
            font-weight: bold;
            width: 60px;
        }

        .cell-date {
            width: 120px;
            text-align: center;
        }

        .cell-name {
            width: 150px;
            font-weight: 500;
        }

        .cell-email {
            width: 200px;
        }

        .cell-message {
            min-width: 300px;
            line-height: 1.6;
            text-align: left;
        }

        .cell-actions {
            text-align: center;
            width: 120px;
        }

        .cell-actions button {
            display: inline-block;
            background: #ffffff;
            color: #000000;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .cell-actions button:hover {
            background: #ff0000;
            color: #ffffff;
        }

        .back-link {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 100;
        }

        .back-link a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            background: #ffffff;
            color: #000000;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
            display: inline-block;
        }

        .back-link a:hover {
            background: #ff0000;
            color: #ffffff;
        }

        @media screen and (max-width: 1200px) {
            body {
                padding: 30px 1%;
            }

            h1 {
                font-size: 36px;
                margin-bottom: 20px;
            }

            table th,
            table td {
                padding: 12px 8px;
                font-size: 12px;
            }

            .cell-message {
                min-width: 200px;
            }
        }

        @media screen and (max-width: 768px) {
            body {
                padding: 20px 1%;
            }

            h1 {
                font-size: 28px;
            }

            table {
                font-size: 11px;
            }

            table th,
            table td {
                padding: 8px 5px;
            }

            .cell-actions button {
                padding: 6px 10px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h1>Gestion des messages de contact</h1>
        
        <?php if($successMessage): ?>
            <div class="success"><?= $successMessage ?></div>
        <?php endif; ?>

        <?php if(empty($messages)): ?>
            <div class="empty-message">Aucun message trouvé.</div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th class="cell-id">ID</th>
                            <th class="cell-date">Date</th>
                            <th class="cell-name">Nom</th>
                            <th class="cell-email">Email</th>
                            <th class="cell-message">Message</th>
                            <th class="cell-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($messages as $msg): ?>
                            <tr>
                                <td class="cell-id"><?= htmlspecialchars($msg['id']) ?></td>
                                <td class="cell-date"><?= htmlspecialchars($msg['date']) ?></td>
                                <td class="cell-name"><?= htmlspecialchars($msg['name']) ?></td>
                                <td class="cell-email"><?= htmlspecialchars($msg['email']) ?></td>
                                <td class="cell-message"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                                <td class="cell-actions">
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer ce message ?');">
                                        <input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
                                        <button type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="back-link">
        <a href="dashboard.php">← Retour au dashboard</a>
    </div>
</body>
</html>
