<?php
session_start();
require_once 'inc/db.php';
require_once 'inc/utils.php';
requiresLogin();
if (!isTeacher()) { header('Location:index.php'); exit; }

$id = intval($_GET['id'] ?? 0);
$uid = $_SESSION['user']['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM resources WHERE id=? AND teacher_id=?");
mysqli_stmt_bind_param($stmt,'ii',$id,$uid);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if(!$row=mysqli_fetch_assoc($res)){
    echo "Ressource non trouvée"; exit;
}

$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $titre = nettoyerTexte($_POST['titre']);
    $description = trim($_POST['description']);
    $tags = tagsToString(explode(',', $_POST['tags'] ?? ''));
    $visibility = $_POST['visibility'];
    $fupdate='';
    if(!empty($_FILES['fichier']['name'])){
        $fn = $_FILES['fichier']['name'];
        if(!preg_match('/\.(pdf|docx|ppt|zip)$/i',$fn)) $err='Format non supporté';
        else {
            $targetDir='ressources/';
            $filename = time().'_'.preg_replace('/[^A-Za-z0-9_\.\-]/','_',basename($fn));
            $dest = $targetDir.$filename;
            move_uploaded_file($_FILES['fichier']['tmp_name'],$dest);
            if(file_exists($row['fichier'])) @unlink($row['fichier']);
            $fupdate=", fichier='".mysqli_real_escape_string($conn,$dest)."'";
        }
    }
    $q="UPDATE resources SET titre='".mysqli_real_escape_string($conn,$titre)."', description='".mysqli_real_escape_string($conn,$description)."', tags='".mysqli_real_escape_string($conn,$tags)."', visibility='".mysqli_real_escape_string($conn,$visibility)."' $fupdate WHERE id=$id AND teacher_id=$uid";
    if(!$err && mysqli_query($conn,$q)) header('Location: dashboard_teacher.php');
    else $err=$err ?: 'Erreur mise à jour';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Editer ressource</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
<h1>Modifier ressource</h1>
<?php if($err): ?><p class="error"><?=htmlspecialchars($err)?></p><?php endif; ?>
<form method="post" action="edit.php?id=<?=$id?>" enctype="multipart/form-data" id="editForm">
<label>Titre<input name="titre" value="<?=htmlspecialchars($row['titre'])?>" required></label>
<label>Description<textarea name="description"><?=htmlspecialchars($row['description'])?></textarea></label>
<label>Tags<input name="tags" value="<?=htmlspecialchars($row['tags'])?>"></label>
<label>Fichier remplacement<input type="file" name="fichier"></label>
<label>Visibilité
<select name="visibility">
<option <?= $row['visibility']=='Public'?'selected':''?>>Public</option>
<option <?= $row['visibility']=='Privé'?'selected':''?>>Privé</option>
<option <?= $row['visibility']=='Restreint'?'selected':''?>>Restreint</option>
</select>
</label>
<button type="submit">Enregistrer</button>
</form>
</div>
<script>
document.getElementById('editForm').addEventListener('submit',function(e){
    console.log('Edit form submitted');
});
</script>
</body>
</html>
