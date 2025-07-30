<?php
// api/login.php

// Affiche les erreurs en dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Renvoie toujours du JSON
header('Content-Type: application/json');

// Démarre la session PHP
session_start();

// Ne gère que le POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Lecture du JSON envoyé
$input = json_decode(file_get_contents('php://input'), true);
$password = trim($input['password'] ?? '');

if ($password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Mot de passe requis']);
    exit;
}

// Vérification du mot de passe
require_once 'config.php';
if (password_verify($password, ADMIN_PASSWORD_HASH)) {
    // Succès : on marque l’admin connecté
    $_SESSION['is_admin'] = true;
    echo json_encode(['success' => true]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Mot de passe incorrect']);
}