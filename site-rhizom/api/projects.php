<?php
// api/projects.php

// Affichage des erreurs en dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// JSON en sortie
header('Content-Type: application/json');

// Démarre la session PHP
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
    echo json_encode(['error' => 'Connexion à la base impossible']);
    exit;
}

// Sur POST, PUT, DELETE, on vérifie que l'admin est connecté
if (in_array($_SERVER['REQUEST_METHOD'], ['POST','PUT','DELETE'])) {
    if (empty($_SESSION['is_admin'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Non autorisé']);
        exit;
    }
}

// ---------------------------------------------
// GET (public) : liste des projets
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt     = $pdo->query("SELECT * FROM projects");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($projects as &$proj) {
            $proj['url'] = '/uploads/' . $proj['url'];
        }
        echo json_encode($projects);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur récupération projets']);
    }
    exit;
}

// ---------------------------------------------
// POST (admin) : création d’un projet
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['title']) || !isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champs manquants']);
        exit;
    }

    $title     = trim($_POST['title']);
    $image     = $_FILES['image'];
    $targetDir = __DIR__ . '/../uploads/';
    $filename  = uniqid() . '_' . basename($image['name']);
    $targetFile= $targetDir . $filename;

    if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
        http_response_code(500);
        echo json_encode(['error' => 'Échec du téléchargement']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO projects (title, url) VALUES (?, ?)");
        $stmt->execute([$title, $filename]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur insertion en base']);
    }
    exit;
}

// ---------------------------------------------
// PUT (admin) : mise à jour du titre
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data  = json_decode(file_get_contents('php://input'), true);
    $id    = $data['id'] ?? null;
    $title = trim($data['title'] ?? '');

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

// ---------------------------------------------
// DELETE (admin) : suppression d’un projet
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id && strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
        $data = json_decode(file_get_contents('php://input'), true);
        $id   = $data['id'] ?? null;
    }
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID manquant']);
        exit;
    }

    try {
        // Récupère le nom de fichier
        $stmt = $pdo->prepare("SELECT url FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $proj = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$proj) {
            http_response_code(404);
            echo json_encode(['error' => 'Projet non trouvé']);
            exit;
        }

        // Supprime le fichier physique
        $imgPath = __DIR__ . '/../uploads/' . $proj['url'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Supprime la ligne en base
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur suppression']);
    }
    exit;
}

// ---------------------------------------------
// Méthode non supportée
// ---------------------------------------------
http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);
