<?php
// api/home_hero.php

// Affichage des erreurs en développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarre la session PHP pour gérer l'authentification
session_start();

// Toutes les réponses sont en JSON
header('Content-Type: application/json');

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

// Si c'est une requête qui modifie (POST ou DELETE), on vérifie la session admin
if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'DELETE'])) {
    if (empty($_SESSION['is_admin'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Non autorisé']);
        exit;
    }
}

// ---------------------------------------------
// GET (public)
//  - /api/home_hero.php            => liste complète
//  - /api/home_hero.php?mode=random => 1 image aléatoire active
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $mode = $_GET['mode'] ?? null;

    try {
        if ($mode === 'random') {
            // Retourne une image active au hasard
            $stmt = $pdo->query("SELECT * FROM hero_images WHERE is_active = 1 ORDER BY RAND() LIMIT 1");
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($img) {
                $img['url'] = '/uploads/' . $img['url'];
                echo json_encode($img);
            } else {
                echo json_encode(null);
            }
        } else {
            // Liste complète
            $stmt = $pdo->query("SELECT * FROM hero_images ORDER BY 
                                 CASE WHEN display_order IS NULL THEN 1 ELSE 0 END, display_order ASC, id DESC");
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($images as &$img) {
                $img['url'] = '/uploads/' . $img['url'];
            }
            echo json_encode($images);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur récupération hero']);
    }
    exit;
}

// ---------------------------------------------
// POST (admin) : upload d'une nouvelle image
// champs attendus : image (file) + alt (optionnel)
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alt = isset($_POST['alt']) ? trim($_POST['alt']) : null;
    if ($alt === '') $alt = null;

    // Normaliser en tableau
    $files = [];
    if (isset($_FILES['images'])) {
        $count = count($_FILES['images']['name']);
        for ($i = 0; $i < $count; $i++) {
            $files[] = [
                'name'     => $_FILES['images']['name'][$i],
                'type'     => $_FILES['images']['type'][$i],
                'tmp_name' => $_FILES['images']['tmp_name'][$i],
                'error'    => $_FILES['images']['error'][$i],
                'size'     => $_FILES['images']['size'][$i],
            ];
        }
    } elseif (isset($_FILES['image'])) {
        $files[] = $_FILES['image'];
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Image(s) manquante(s)']);
        exit;
    }

    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir)) @mkdir($targetDir, 0775, true);

    $allowedExts = ['jpg','jpeg','png','webp','gif'];
    $insert = $pdo->prepare("INSERT INTO hero_images (url, alt) VALUES (?, ?)");

    $saved = [];
    foreach ($files as $image) {
        if (!empty($image['error'])) continue;

        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExts)) continue;

        $filename = uniqid('hero_', true) . '_' . basename($image['name']);
        $targetFile = $targetDir . $filename;

        if (!move_uploaded_file($image['tmp_name'], $targetFile)) continue;

        try {
            $insert->execute([$filename, $alt]);
            $saved[] = $filename;
        } catch (PDOException $e) {
            @unlink($targetFile);
        }
    }

    if (!$saved) {
        http_response_code(400);
        echo json_encode(['error' => 'Aucune image valide enregistrée']);
        exit;
    }

    echo json_encode(['success' => true, 'count' => count($saved)]);
    exit;
}

// ---------------------------------------------
// DELETE (admin) : suppression d'une image (file + DB)
//  - /api/home_hero.php?id=123
//  - ou body JSON: { "id": 123 }
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;

    if (!$id && !empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
    }

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID manquant']);
        exit;
    }

    try {
        // Récupère l'URL pour supprimer le fichier
        $stmt = $pdo->prepare("SELECT url FROM hero_images WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$img) {
            http_response_code(404);
            echo json_encode(['error' => 'Image non trouvée']);
            exit;
        }

        $imgPath = __DIR__ . '/../uploads/' . $img['url'];
        if (file_exists($imgPath)) {
            @unlink($imgPath);
        }

        // Supprime la ligne
        $stmt = $pdo->prepare("DELETE FROM hero_images WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la suppression']);
    }
    exit;
}

// Méthode non supportée
http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);
