<?php
session_start();
require_once 'inc/db.php';
require_once 'inc/utils.php';
requiresLogin();
if (!isTeacher()) { header('Location:index.php'); exit; }

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_SESSION['user']['id'];
    $type = $_POST['type'] ?? '';
    $titre = nettoyerTexte($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $module = $_POST['module'] ?? '';
    $niveau = $_POST['niveau'] ?? '';
    $visibility = $_POST['visibility'] ?? 'Public';
    $tags = tagsToString(explode(',', $_POST['tags'] ?? ''));
    $restricted_niveaux = '';
    $restricted_groupes = '';

    // Gestion des restrictions
    if ($visibility === 'Restreint') {
        $restricted_niveaux = implode(',', $_POST['niveaux_restreints'] ?? []);
        $restricted_groupes = implode(',', $_POST['groupes_restreints'] ?? []);
    }

    if (!isset($_FILES['fichier'])) { $err = 'Fichier manquant'; }
    else {
        $fn = $_FILES['fichier']['name'];
        if (!preg_match('/\.(pdf|docx|ppt|zip)$/i', $fn)) $err = 'Format non supporté (PDF, DOCX, PPT, ZIP seulement)';
        else {
            $targetDir = 'ressources/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\.\-]/', '_', basename($fn));
            $dest = $targetDir . $filename;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $dest)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO resources(teacher_id,type,titre,description,fichier,module,niveau,tags,visibility,restricted_to_niveaux,restricted_to_groupes) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'issssssssss', $teacher_id, $type, $titre, $description, $dest, $module, $niveau, $tags, $visibility, $restricted_niveaux, $restricted_groupes);
                if (mysqli_stmt_execute($stmt)) {
                    header('Location: dashboard_teacher.php?msg=Resource uploaded successfully');
                    exit;
                }
                else $err = 'Erreur insertion DB: ' . mysqli_error($conn);
            } else $err = 'Erreur upload du fichier';
        }
    }
}

// Récupérer les modules et niveaux disponibles
$modules = [];
$res = mysqli_query($conn, "SELECT nom FROM modules ORDER BY nom");
while($r = mysqli_fetch_assoc($res)) $modules[] = $r['nom'];

$niveaux = ['CP1', 'CP2', 'CI1', 'CI2', 'CI3'];
$groupes = ['Groupe 1', 'Groupe 2', 'Groupe 3', 'Groupe 4', 'Groupe 5'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter une ressource</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
<h1>📤 Ajouter une ressource</h1>
<?php if($err): ?><div class="alert alert-error"><?=htmlspecialchars($err)?></div><?php endif; ?>

<form id="uploadForm" method="post" action="upload.php" enctype="multipart/form-data" class="form-upload">
    
    <div class="form-section">
        <h3>Informations générales</h3>
        
        <div class="form-group">
            <label>Type de ressource *</label>
            <select name="type" required>
                <option value="">-- Sélectionnez --</option>
                <option value="Cours">📖 Cours</option>
                <option value="TP">🔬 Travaux Pratiques (TP)</option>
                <option value="Examen">📝 Examen</option>
                <option value="Corrigé">✅ Corrigé</option>
                <option value="Autre">📎 Autre</option>
            </select>
        </div>

        <div class="form-group">
            <label>Titre *</label>
            <input type="text" name="titre" required placeholder="ex: Introduction aux bases de données">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5" placeholder="Résumé du contenu, objectifs d'apprentissage..."></textarea>
        </div>
    </div>

    <div class="form-section">
        <h3>Classification</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label>Module *</label>
                <select name="module" required>
                    <option value="">-- Sélectionnez --</option>
                    <?php foreach($modules as $m): ?>
                    <option value="<?=htmlspecialchars($m)?>"><?=htmlspecialchars($m)?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Niveau *</label>
                <select name="niveau" required>
                    <option value="">-- Sélectionnez --</option>
                    <?php foreach($niveaux as $n): ?>
                    <option value="<?=$n?>"><?=$n?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Tags (séparés par des virgules)</label>
            <input type="text" name="tags" placeholder="ex: SQL, Normalisation, Relationnel">
            <small>Aide les étudiants à trouver votre ressource</small>
        </div>
    </div>

    <div class="form-section">
        <h3>Fichier</h3>
        <div class="form-group">
            <label>Fichier *</label>
            <input type="file" name="fichier" required accept=".pdf,.docx,.ppt,.zip">
            <small>Formats acceptés: PDF, DOCX, PPT, ZIP (Max 50MB)</small>
        </div>
    </div>

    <div class="form-section">
        <h3>Droits d'accès</h3>
        
        <div class="form-group">
            <label>Visibilité *</label>
            <select name="visibility" id="visibilitySelect" required>
                <option value="Public">🌍 Public - Tous les étudiants</option>
                <option value="Restreint">🔒 Restreint - Niveaux/Groupes sélectionnés</option>
                <option value="Privé">🔐 Privé - Personne ne peut accéder</option>
            </select>
        </div>

        <div id="restrictedFields" style="display:none;">
            <div class="form-group">
                <label>Restreindre par niveau</label>
                <div class="checkbox-group">
                    <?php foreach($niveaux as $n): ?>
                    <label class="checkbox">
                        <input type="checkbox" name="niveaux_restreints" value="<?=$n?>">
                        <?=$n?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Restreindre par groupe</label>
                <div class="checkbox-group">
                    <?php foreach($groupes as $g): ?>
                    <label class="checkbox">
                        <input type="checkbox" name="groupes_restreints" value="<?=$g?>">
                        <?=$g?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">📤 Uploader la ressource</button>
        <a href="dashboard_teacher.php" class="btn btn-secondary">Annuler</a>
    </div>
</form>
</div>

<script>
document.getElementById('visibilitySelect').addEventListener('change', function(){
    const restrictedFields = document.getElementById('restrictedFields');
    if (this.value === 'Restreint') {
        restrictedFields.style.display = 'block';
    } else {
        restrictedFields.style.display = 'none';
    }
});

document.getElementById('uploadForm').addEventListener('submit', function(e){
    if(document.querySelector('input[name="fichier"]').files.length === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner un fichier');
    }
});
</script>
</body>
</html>
