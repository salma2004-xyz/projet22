<?php
session_start();
require_once 'inc/db.php';
requiresLogin();
if (!isStudent()) { header('Location:index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Étudiant</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
<h1>Bienvenue <?=htmlspecialchars($_SESSION['user']['username'])?></h1>
<p><a href="search.php">Rechercher des ressources</a></p>
<h3>Dernières ressources publiques</h3>
<ul>
<?php
$res = mysqli_query($conn, "SELECT r.*, u.username FROM resources r JOIN users u ON r.teacher_id=u.id WHERE r.visibility='Public' ORDER BY r.date_add DESC LIMIT 10");
while($row = mysqli_fetch_assoc($res)):
?>
<li><a href="view.php?id=<?=$row['id']?>"><?=htmlspecialchars($row['titre'])?></a> — <?=htmlspecialchars($row['username'])?> (<?=$row['module']?>)</li>
<?php endwhile; ?>
</ul>
</div>
<script>
console.log('Dashboard étudiant chargé pour <?=addslashes($_SESSION['user']['username'])?>');
</script>
</body>
</html>
