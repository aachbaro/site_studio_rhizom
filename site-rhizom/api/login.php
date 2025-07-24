<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';
require_once 'vendor/autoload.php'; // pour JWT

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Lecture du body JSON
$input = json_decode(file_get_contents('php://input'), true);
$password = $input['password'] ?? '';

if (!$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Mot de passe requis']);
    exit;
}

// Vérification du mot de passe
if (password_verify($password, ADMIN_PASSWORD_HASH)) {
    // Mot de passe correct → Générer le JWT
    $payload = [
        'admin' => true,
        'exp' => time() + 60 * 60 * 24 // expire dans 24h
    ];
    $jwt = JWT::encode($payload, JWT_SECRET, 'HS256');
    echo json_encode(['token' => $jwt]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Mot de passe incorrect']);
}