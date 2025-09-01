<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifie la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupère les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$objet = trim($data['objet'] ?? '');  // <-- AJOUTÉ
$message = trim($data['message'] ?? '');

if (!$name || !$email || !$objet || !$message) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants']);
    exit;
}

// Vérifie l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => "Email invalide"]);
    exit;
}

try {
    $mail = new PHPMailer(true);
    // Config SMTP
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // 'ssl'
    $mail->Port = SMTP_PORT;

    // Destinataires
    $mail->setFrom(SMTP_USER, $name);
    $mail->addAddress(DEST_EMAIL);

    $mail->addReplyTo($email, $name);
    

    // Contenu
    $mail->isHTML(false);
    $mail->Subject = $objet;  // <-- UTILISATION DE L’OBJET DU FORMULAIRE
    $mail->Body    = "Nom : $name\nEmail : $email\nObjet : $objet\nMessage :\n$message";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}"]);
}