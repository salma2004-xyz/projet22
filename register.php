<?php
session_start();
require_once 'inc/db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $niveau = $_POST['niveau'] ?: null;
    $groupe = $_POST['groupe'] ?: null;
    if (registerUser($username, $password, $role, $niveau, $groupe)) {
        $msg = 'Inscription réussie. Vous pouvez vous connecter.';
    } else {
        $msg = 'Erreur ou utilisateur déjà existant.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription - Plateforme</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Inscription</h1>
    <?php if($msg): ?><p><?=htmlspecialchars($msg)?></p><?php endif; ?>
    <form method="post" action="register.php" id="regForm">
        <label>Nom d'utilisateur<input name="username" required></label>
        <label>Mot de passe<input type="password" name="password" required></label>
        <label>Rôle
            <select name="role" id="roleSel">
                <option value="student">Étudiant</option>
                <option value="teacher">Enseignant</option>
                <option value="admin">Administrateur</option>
            </select>
        </label>
        <div id="extraFields" style="display:none">
            <label>Niveau<input name="niveau"></label>
            <label>Groupe<input name="groupe"></label>
        </div>
        <button type="submit">S'inscrire</button>
    </form>
    <p><a href="index.php">Retour connexion</a></p>
</div>

<script>
document.getElementById('roleSel').addEventListener('change', function(){
    if (this.value === 'student' || this.value === 'teacher') {
        document.getElementById('extraFields').style.display = 'block';
    } else {
        document.getElementById('extraFields').style.display = 'none';
    }
});
</script>
</body>
</html>
