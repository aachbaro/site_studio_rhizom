<?php
// api/projects.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
session_start();

require_once 'config.php';

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

// === Override de la méthode HTTP (pour _method) ===
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// === Vérif session pour méthodes protégées ===
if (in_array($method, ['POST', 'PUT', 'DELETE']) && empty($_SESSION['is_admin'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

/**
 * Helper: normalise l'URL publique du fichier
 */
function public_url($filename) {
    return '/uploads/' . ltrim($filename, '/');
}

// -------------------------------------------------
// BLOC SPÉCIAL : POST JSON { action: "reorder", ids }
// -> doit être traité AVANT la création multipart
// -------------------------------------------------
if ($method === 'POST' && !empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if (($data['action'] ?? null) === 'reorder') {
        $ids = $data['ids'] ?? null;
        if (!is_array($ids) || empty($ids)) {
            http_response_code(400);
            echo json_encode(['error' => 'Liste d’IDs invalide']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            $pos = 1;
            $stmt = $pdo->prepare("UPDATE projects SET display_order = ? WHERE id = ?");
            foreach ($ids as $pid) {
                $pid = (int)$pid;
                $stmt->execute([$pos, $pid]);
                $pos++;
            }

            $pdo->commit();
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du réordonnancement']);
        }
        exit;
    }
}

// === ROUTAGE CLASSIQUE ===
if ($method === 'GET') {
    // Liste des projets triés par display_order
    try {
        $stmt = $pdo->query("
            SELECT id, title, url, display_order
            FROM projects
            ORDER BY
              CASE WHEN display_order IS NULL THEN 1 ELSE 0 END,
              display_order ASC,
              id DESC
        ");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($projects as &$proj) {
            $proj['url'] = public_url($proj['url']);
        }
        echo json_encode($projects);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur récupération projets']);
    }
    exit;
}

if ($method === 'POST') {
    // Création d’un projet (multipart/form-data)
    if (!isset($_POST['title']) || !isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champs manquants']);
        exit;
    }

    $title = trim($_POST['title']);
    $image = $_FILES['image'];

    // Dossier d’upload
    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0775, true);
    }

    // Nom de fichier
    $filenameOriginal = basename($image['name']);
    $filename = uniqid('proj_', true) . '_' . $filenameOriginal;
    $targetFile = $targetDir . $filename;

    if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
        http_response_code(500);
        echo json_encode(['error' => 'Échec du téléchargement']);
        exit;
    }

    try {
        // Position en fin de liste
        $max = $pdo->query("SELECT COALESCE(MAX(display_order), 0) AS m FROM projects")
                   ->fetch(PDO::FETCH_ASSOC)['m'];
        $newPos = (int)$max + 1;

        $stmt = $pdo->prepare("INSERT INTO projects (title, url, display_order) VALUES (?, ?, ?)");
        $stmt->execute([$title, $filename, $newPos]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        if (file_exists($targetFile)) @unlink($targetFile);
        http_response_code(500);
        echo json_encode(['error' => 'Erreur insertion en base']);
    }
    exit;
}

if ($method === 'PUT') {
    // Mise à jour du titre
    $id    = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');

    if (!$id || $title === '') {
        http_response_code(400);
        echo json_encode(['error' => 'ID et titre obligatoires']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE projects SET title = ? WHERE id = ?");
        $stmt->execute([$title, $id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur mise à jour']);
    }
    exit;
}

if ($method === 'DELETE') {
    // Suppression (fichier + DB)
    $id = $_POST['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID manquant']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT url FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $proj = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$proj) {
            http_response_code(404);
            echo json_encode(['error' => 'Projet non trouvé']);
            exit;
        }

        $path = __DIR__ . '/../uploads/' . $proj['url'];
        if (file_exists($path)) @unlink($path);

        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur suppression']);
    }
    exit;
}

// Méthode non supportée
http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);
