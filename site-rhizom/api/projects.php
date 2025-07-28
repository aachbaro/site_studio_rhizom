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

// ---- GET : Liste des projets ----
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM projects");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($projects);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => "Erreur lors de la récupération des projets"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdminAuth();

    if (!isset($_POST['title'], $_POST['description']) || !isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champs manquants']);
        exit;
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    $targetDir = __DIR__ . '/../uploads/';
    $filename = uniqid() . '_' . basename($image['name']);
    $targetFile = $targetDir . $filename;

    if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
        http_response_code(500);
        echo json_encode(['error' => 'Échec du téléchargement de l’image']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO projects (title, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $filename]);

    echo json_encode(['success' => true]);
}

// ---- DELETE : Supprimer un projet (admin) ----
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    requireAdminAuth();

    // Récupère l'id du projet à supprimer (via ?id=xxx ou body JSON)
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
        $stmt = $pdo->prepare("SELECT url FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $proj = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$proj) {
            http_response_code(404);
            echo json_encode(['error' => "Projet non trouvé"]);
            exit;
        }
        // Supprime le fichier image
        $imgPath = __DIR__ . '/../' . ltrim($proj['url'], '/');
        if (file_exists($imgPath)) unlink($imgPath);

        // Supprime la ligne en base
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
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
