<?php
session_start();
require_once 'inc/db.php';
requiresLogin();
if (!isAdmin()) { header('Location:index.php'); exit; }

$perType = [];
$res = mysqli_query($conn, "SELECT type, COUNT(*) as c FROM resources GROUP BY type");
while($r = mysqli_fetch_assoc($res)) $perType[$r['type']] = $r['c'];

$top = mysqli_query($conn, "SELECT r.id, r.titre, COUNT(d.id) as cnt FROM resources r LEFT JOIN downloads d ON r.id=d.res_id GROUP BY r.id ORDER BY cnt DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
<h1>Administration</h1>
<h3>Ressources par type</h3>
<ul>
<?php foreach($perType as $k=>$v): ?>
<li><?=htmlspecialchars($k)?> : <?=$v?></li>
<?php endforeach; ?>
</ul>

<h3>Top téléchargées</h3>
<ol>
<?php while($r=mysqli_fetch_assoc($top)): ?>
<li><?=htmlspecialchars($r['titre'])?> (<?=$r['cnt']?>)</li>
<?php endwhile; ?>
</ol>
</div>
</body>
</html>
