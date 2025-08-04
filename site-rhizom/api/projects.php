<?php
// api/projects.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
session_start();

require_once 'config.php';

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error'=>'Connexion à la base impossible']);
    exit;
}

// === Override de la méthode ===
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// === Vérif session pour méthodes protégées ===
if (in_array($method, ['POST','PUT','DELETE']) && empty($_SESSION['is_admin'])) {
    http_response_code(401);
    echo json_encode(['error'=>'Non autorisé']);
    exit;
}

// === ROUTAGE ===
if ($method === 'GET') {
    // --- liste des projets (inchangé) ---
    try {
        $stmt     = $pdo->query("SELECT * FROM projects");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($projects as &$proj) {
            $proj['url'] = '/uploads/' . $proj['url'];
        }
        echo json_encode($projects);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error'=>'Erreur récupération projets']);
    }
    exit;
}

if ($method === 'POST') {
    // --- création d’un projet (inchangé) ---
    if (!isset($_POST['title']) || !isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['error'=>'Champs manquants']);
        exit;
    }
    $title     = trim($_POST['title']);
    $image     = $_FILES['image'];
    $targetDir = __DIR__ . '/../uploads/';
    $filename  = uniqid().'_'.basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $targetDir.$filename)) {
        http_response_code(500);
        echo json_encode(['error'=>'Échec du téléchargement']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO projects (title,url) VALUES (?,?)");
        $stmt->execute([$title, $filename]);
        echo json_encode(['success'=>true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error'=>'Erreur insertion en base']);
    }
    exit;
}

if ($method === 'PUT') {
    // --- mise à jour du titre ---
    $id    = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    if (!$id || $title==='') {
        http_response_code(400);
        echo json_encode(['error'=>'ID et titre obligatoires']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("UPDATE projects SET title=? WHERE id=?");
        $stmt->execute([$title, $id]);
        echo json_encode(['success'=>true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error'=>'Erreur mise à jour']);
    }
    exit;
}

if ($method === 'DELETE') {
    // --- suppression ---
    $id = $_POST['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error'=>'ID manquant']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("SELECT url FROM projects WHERE id=?");
        $stmt->execute([$id]);
        $proj = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$proj) {
            http_response_code(404);
            echo json_encode(['error'=>'Projet non trouvé']);
            exit;
        }
        $path = __DIR__.'/../uploads/'.$proj['url'];
        if (file_exists($path)) unlink($path);
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success'=>true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error'=>'Erreur suppression']);
    }
    exit;
}

// --- méthode non supportée ---
http_response_code(405);
echo json_encode(['error'=>'Méthode non autorisée']);