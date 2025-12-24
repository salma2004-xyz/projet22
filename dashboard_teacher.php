<?php
session_start();
require_once 'inc/db.php';
require_once 'inc/utils.php';
requiresLogin();
if (!isTeacher()) { header('Location: index.php'); exit; }
$uid = $_SESSION['user']['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM resources WHERE teacher_id=? ORDER BY date_add DESC");
mysqli_stmt_bind_param($stmt, 'i', $uid);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Enseignant</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
<h1>Mes ressources</h1>
<p><a href="upload.php">Ajouter une ressource</a></p>
<table class="table">
<thead><tr><th>Titre</th><th>Type</th><th>Module</th><th>Vues</th><th>Téléchargements</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)):
$resId = $row['id'];
$v = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM views WHERE res_id=$resId"))['c'] ?? 0;
$d = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM downloads WHERE res_id=$resId"))['c'] ?? 0;
?>
<tr>
<td><?=htmlspecialchars($row['titre'])?></td>
<td><?=htmlspecialchars($row['type'])?></td>
<td><?=htmlspecialchars($row['module'])?></td>
<td><?=$v?></td>
<td><?=$d?></td>
<td>
<a href="view.php?id=<?=$resId?>" target="_blank">Voir</a> |
<a href="edit.php?id=<?=$resId?>">Edit</a> |
<a href="delete.php?id=<?=$resId?>" class="del-link">Suppr</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<script>
document.querySelectorAll('.del-link').forEach(function(el){
    el.addEventListener('click', function(e){
        if(!confirm('Confirmer suppression?')) e.preventDefault();
    });
});
</script>
</body>
</html>
