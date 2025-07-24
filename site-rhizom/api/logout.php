<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Pour JWT, il n'y a rien à faire côté serveur.
// Le client doit juste supprimer le token localement.
echo json_encode(['success' => true, 'message' => 'Déconnexion réussie.']);