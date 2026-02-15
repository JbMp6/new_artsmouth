<?php
// Fichier pour traiter les messages de contact

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupération des données
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Tous les champs sont requis']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email invalide']);
    exit;
}

// Préparer les données du message
$contactMessage = [
    'id' => uniqid(),
    'date' => date('Y-m-d H:i:s'),
    'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
    'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
    'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
];

// Chemin du fichier JSON (chemin absolu robuste)
$dataDir = __DIR__ . '/data';
$jsonFile = $dataDir . '/contacts.json';

// Créer le répertoire s'il n'existe pas
if (!is_dir($dataDir)) {
    if (!mkdir($dataDir, 0775, true) && !is_dir($dataDir)) {
        http_response_code(500);
        echo json_encode(['error' => "Impossible de créer le répertoire des données"]);
        exit;
    }
}

// Lire les messages existants
$messages = [];
if (file_exists($jsonFile)) {
    $content = file_get_contents($jsonFile);
    $messages = json_decode($content, true) ?: [];
}

// Ajouter le nouveau message
$messages[] = $contactMessage;

// Sauvegarder dans le fichier JSON avec verrouillage et vérification d'erreur
$jsonPayload = json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($jsonPayload === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur d\'encodage JSON']);
    exit;
}

// Vérifier la possibilité d'écriture
if (file_exists($jsonFile) && !is_writable($jsonFile)) {
    http_response_code(500);
    echo json_encode(['error' => 'Le fichier de données n\'est pas inscriptible']);
    exit;
}

$bytes = @file_put_contents($jsonFile, $jsonPayload, LOCK_EX);
if ($bytes === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Échec de l\'écriture des données']);
    exit;
}

// Rediriger avec un message de succès
header('Location: index.php#contact');
exit;
