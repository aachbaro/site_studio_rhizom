<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// --- Auth admin ---
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

// --- Connexion BDD ---
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

// --- GET projets ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM projects");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($projects as &$proj) {
            $proj['url'] = '/uploads/' . $proj['url'];
        }

        echo json_encode($projects);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => "Erreur lors de la récupération des projets"]);
    }
    exit;
}

// --- POST projet ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdminAuth();

    if (!isset($_POST['title']) || !isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champs manquants']);
        exit;
    }

    $title = $_POST['title'];
    $image = $_FILES['image'];

    $targetDir = __DIR__ . '/../uploads/';
    $filename = uniqid() . '_' . basename($image['name']);
    $targetFile = $targetDir . $filename;

    if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
        http_response_code(500);
        echo json_encode(['error' => 'Échec du téléchargement']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO projects (title, url) VALUES (?, ?)");
    $stmt->execute([$title, $filename]);

    ob_clean();
    echo json_encode(['success' => true]);
    exit;
}

// --- DELETE projet ---
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    requireAdminAuth();

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
        $stmt = $pdo->prepare("SELECT url FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $proj = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$proj) {
            http_response_code(404);
            echo json_encode(['error' => "Projet non trouvé"]);
            exit;
        }

        $imgPath = __DIR__ . '/../uploads/' . ltrim($proj['url'], '/');
        if (file_exists($imgPath)) unlink($imgPath);

        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => "Erreur lors de la suppression"]);
    }
    exit;
}

// --- fallback ---
http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);