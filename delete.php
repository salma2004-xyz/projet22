<?php
session_start();
require_once 'inc/db.php';
requiresLogin();
if (!isTeacher()) { header('Location: index.php'); exit; }

$id = intval($_GET['id'] ?? 0);
$uid = $_SESSION['user']['id'];

// Vérifier que la ressource appartient à l'utilisateur
$stmt = mysqli_prepare($conn, "SELECT fichier FROM resources WHERE id=? AND teacher_id=?");
mysqli_stmt_bind_param($stmt, 'ii', $id, $uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(!$row = mysqli_fetch_assoc($result)) {
    header('Location: dashboard_teacher.php?error=Ressource non trouvée');
    exit;
}

// Supprimer le fichier
if(file_exists($row['fichier'])) {
    unlink($row['fichier']);
}

// Supprimer la ressource
$deleteStmt = mysqli_prepare($conn, "DELETE FROM resources WHERE id=? AND teacher_id=?");
mysqli_stmt_bind_param($deleteStmt, 'ii', $id, $uid);

if(mysqli_stmt_execute($deleteStmt)) {
    header('Location: dashboard_teacher.php?msg=Ressource supprimée');
} else {
    header('Location: dashboard_teacher.php?error=Erreur de suppression');
}
exit;
?>
