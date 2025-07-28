<?php
// api/config.php

// define('DB_HOST', 'studiltdb.mysql.db');
// define('DB_USER', 'studiltdbuser');
// define('DB_PASS', 'RhizomDetre93');
// define('DB_NAME', 'studiltdbuser');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // (vide sur WAMP)
define('DB_NAME', 'studiltdbuser');

// --- SMTP (pour PHPMailer par exemple) ---
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_USER', 'adam.achbarou@gmail.com');
// L’email utilisé pour envoyer (ex: precontact@studiorhizom.com)
define('SMTP_PASS', 'wxinajmwpmdoizrg');
define('DEST_EMAIL', 'contact@studiorhizom.com'); // Destinataire des messages de contact

// Hash de mot de passe admin (si tu fais la vérification par hash)
define('ADMIN_PASSWORD_HASH', '$2b$10$aiXXNuS/3crb2uYsO.TCgu.cPUDqa3uqN0MVPRsSJhLf.6bSb6b9q');

// --- JWT ---
define('JWT_SECRET', 'dh!7q3nL94@dfga1N%pU*b67X1A0akA12zOf8J1sQ48eWxJK22fgoJp');