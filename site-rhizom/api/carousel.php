<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Middleware JWT pour sécuriser POST/DELETE
function requireAdminAuth() {
    $headers = getallheaders();
    $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    if (stripos($auth, 'Bearer ') !== 0) {
        http_response_code(401);
        echo json_encode(['error' => "Accès admin non autorisé"]);
        exit;
    }
    $token = substr($auth, 7);
    try {
        $payload = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if (empty($payload->admin) || !$payload->admin) throw new Exception();
    } catch (Exception $e) {
        http_response_code(403);
        echo json_encode(['error' => "Token invalide ou expiré"]);
        exit;
    }
}

// Connexion à la BDD
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connexion à la base impossible']);
    exit;
}

// ---- GET : Liste des images du carousel ----
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM carousel_images");
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($images as &$img) {
            $img['url'] = '/uploads/' . $img['url']; // ou ajoute directement l'URL complète ici
        }

        echo json_encode($images);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => "Erreur lors de la récupération du carousel"]);
    }
    exit;
}

// ---- POST : Ajouter une image au carousel (admin) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdminAuth();

    if (!isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Image manquante']);
        exit;
    }

    $image = $_FILES['image'];
    $targetDir = __DIR__ . '/../uploads/';
    $filename = uniqid() . '_' . basename($image['name']);
    $targetFile = $targetDir . $filename;
if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Échec du téléchargement',
        'debug' => [
            'tmp_name' => $image['tmp_name'],
            'targetFile' => $targetFile,
            'is_uploaded_file' => is_uploaded_file($image['tmp_name']),
            'file_exists_tmp' => file_exists($image['tmp_name']),
            'writable_target_dir' => is_writable($targetDir),
        ]
    ]);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO carousel_images (url) VALUES (?)");
$stmt->execute([$filename]);
ob_clean();
echo json_encode(['success' => true]);
exit;
}

// ---- DELETE : Supprimer une image (admin) ----
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    requireAdminAuth();

    // Récupère l'id de l'image à supprimer (via ?id=xxx ou body JSON)
    $id = $_GET['id'] ?? null;
    if (!$id && $_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
    }
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => "ID manquant"]);
        exit;
    }

    try {
        // Récupère le chemin de l'image à supprimer
        $stmt = $pdo->prepare("SELECT url FROM carousel_images WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$img) {
            http_response_code(404);
            echo json_encode(['error' => "Image non trouvée"]);
            exit;
        }
        // Supprime le fichier image
        $imgPath = __DIR__ . '/../' . ltrim($img['url'], '/');
        if (file_exists($imgPath)) unlink($imgPath);

        // Supprime la ligne en base
        $stmt = $pdo->prepare("DELETE FROM carousel_images WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => "Erreur lors de la suppression"]);
    }
    exit;
}

// ---- Méthode non autorisée ----
http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);