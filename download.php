<?php
session_start();
require_once 'inc/db.php';
requiresLogin();

$id = intval($_GET['id'] ?? 0);
$user_id = $_SESSION['user']['id'] ?? null;
$username = $_SESSION['user']['username'] ?? 'Anonymous';

// Vérifier l'existence de la ressource et les droits d'accès
$stmt = mysqli_prepare($conn, "SELECT r.*, u.id as teacher_id FROM resources r JOIN users u ON r.teacher_id=u.id WHERE r.id=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(!$resource = mysqli_fetch_assoc($result)) {
    die('Ressource non trouvée');
}

// Vérifier les droits d'accès
if($resource['visibility'] === 'Privé') {
    die('Accès refusé');
}

if($resource['visibility'] === 'Restreint') {
    $user_niveau = $_SESSION['user']['niveau'] ?? '';
    $user_groupe = $_SESSION['user']['groupe'] ?? '';
    
    $has_access = false;
    if(!empty($resource['restricted_to_niveaux']) && strpos($resource['restricted_to_niveaux'], $user_niveau) !== false) {
        $has_access = true;
    }
    if(!empty($resource['restricted_to_groupes']) && strpos($resource['restricted_to_groupes'], $user_groupe) !== false) {
        $has_access = true;
    }
    
    if(!$has_access) {
        die('Accès refusé');
    }
}

// Enregistrer le téléchargement
$downloadStmt = mysqli_prepare($conn, "INSERT INTO downloads(res_id, user_id, username) VALUES(?, ?, ?)");
mysqli_stmt_bind_param($downloadStmt, 'iss', $id, $user_id, $username);
mysqli_stmt_execute($downloadStmt);

// Télécharger le fichier
$file_path = $resource['fichier'];
if(file_exists($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
} else {
    die('Fichier non trouvé');
}
?>
