<?php
// Configuration de l'application

// Informations du site
define('SITE_NAME', 'Plateforme Pédagogique');
define('SITE_URL', 'http://localhost/projet2');
define('ADMIN_EMAIL', 'admin@example.com');

// Base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'projet2');

// Dossiers
define('UPLOAD_DIR', __DIR__ . '/../ressources/');
define('UPLOAD_LIMIT', 50 * 1024 * 1024); // 50MB

// Formats autorisés
define('ALLOWED_EXTENSIONS', ['pdf', 'docx', 'ppt', 'zip']);
define('ALLOWED_MIME_TYPES', [
    'application/pdf',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/zip'
]);

// Types de ressources
define('RESOURCE_TYPES', ['Cours', 'TP', 'Examen', 'Corrigé', 'Autre']);

// Niveaux
define('LEVELS', ['CP1', 'CP2', 'CI1', 'CI2', 'CI3']);

// Groupes
define('GROUPS', ['Groupe 1', 'Groupe 2', 'Groupe 3', 'Groupe 4', 'Groupe 5']);

// Modules (à récupérer depuis la base de données en production)
$modules = ['Informatique', 'Mathématiques', 'Physique', 'Chimie', 'Langues', 'Économie', 'Droit'];

// Rôles
define('ROLES', ['student', 'teacher', 'admin']);

// Sécurité
define('SESSION_TIMEOUT', 3600); // 1 heure
define('PASSWORD_MIN_LENGTH', 6);

// Pagination
define('ITEMS_PER_PAGE', 10);
define('SIMILAR_ITEMS', 5);

// Logs
define('LOG_DIR', __DIR__ . '/../logs/');
define('DEBUG_MODE', false); // À mettre à false en production

// Fonctions utilitaires
function config($key, $default = null) {
    $config = [
        'site_name' => SITE_NAME,
        'site_url' => SITE_URL,
        'upload_limit' => UPLOAD_LIMIT,
        'items_per_page' => ITEMS_PER_PAGE,
    ];
    return $config[$key] ?? $default;
}

function is_valid_extension($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ALLOWED_EXTENSIONS);
}

function log_activity($user_id, $action, $details = '') {
    if (!DEBUG_MODE) return;
    
    $log_file = LOG_DIR . date('Y-m-d') . '.log';
    if (!is_dir(LOG_DIR)) mkdir(LOG_DIR, 0755, true);
    
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] User: $user_id | Action: $action | Details: $details\n";
    file_put_contents($log_file, $message, FILE_APPEND);
}

// Constantes de retours d'erreur
define('ERR_DB_CONNECTION', 'Erreur de connexion à la base de données');
define('ERR_INVALID_CREDENTIALS', 'Identifiants invalides');
define('ERR_USER_EXISTS', 'Cet utilisateur existe déjà');
define('ERR_UPLOAD_FAILED', 'Erreur lors de l\'upload du fichier');
define('ERR_FILE_NOT_FOUND', 'Fichier non trouvé');
define('ERR_UNAUTHORIZED', 'Accès non autorisé');
define('ERR_SESSION_EXPIRED', 'Votre session a expiré');

define('SUCCESS_UPLOAD', 'Ressource uploadée avec succès');
define('SUCCESS_UPDATE', 'Ressource mise à jour avec succès');
define('SUCCESS_DELETE', 'Ressource supprimée avec succès');
define('SUCCESS_LOGIN', 'Connexion réussie');

?>
