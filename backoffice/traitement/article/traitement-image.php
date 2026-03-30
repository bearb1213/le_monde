<?php
// Simple TinyMCE image upload: saves to /image and returns { location }
header('Content-Type: application/json');
// Renvoie d'erreur
function fail(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit;
}

$input = 'file';                 
$webDir = '/image';              
$absDir = __DIR__ . '/../../../image';
$allowed = ['jpg','jpeg','png','gif','webp'];

if (!isset($_FILES[$input])) {
    fail('Aucun fichier reçu');
}

$f = $_FILES[$input];
if ($f['error'] !== UPLOAD_ERR_OK) {
    fail('Erreur upload', 500);
}

if (!is_dir($absDir) && !mkdir($absDir, 0777, true)) {
    fail('Impossible de créer le dossier upload', 500);
}
if (!is_writable($absDir)) {
    fail('Dossier upload non accessible en écriture', 500);
}

$ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true)) {
    fail('Format non autorisé');
}

if (@getimagesize($f['tmp_name']) === false) {
    fail("Le fichier n'est pas une image valide");
}

$name = uniqid('img_', true) . '.' . $ext;
$dest = rtrim($absDir, '/\\') . '/' . $name;

if (!move_uploaded_file($f['tmp_name'], $dest)) {
    fail("Impossible d'enregistrer l'image", 500);
}

// TinyMCE expects a full/relative URL in "location"
echo json_encode(['location' => rtrim($webDir, '/') . '/' . $name]);