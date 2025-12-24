<?php
session_start();
require_once 'inc/db.php';
requiresLogin();

$id = intval($_GET['id'] ?? 0);
$user_id = $_SESSION['user']['id'] ?? null;

// Récupérer la ressource
$stmt = mysqli_prepare($conn, "SELECT r.*, u.username FROM resources r JOIN users u ON r.teacher_id=u.id WHERE r.id=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(!$resource = mysqli_fetch_assoc($result)) {
    die('Ressource non trouvée');
}

// Vérifier les droits d'accès
$can_access = false;
if($resource['visibility'] === 'Public') {
    $can_access = true;
} elseif($resource['visibility'] === 'Restreint' && isset($_SESSION['user'])) {
    $user_niveau = $_SESSION['user']['niveau'] ?? '';
    $user_groupe = $_SESSION['user']['groupe'] ?? '';
    
    if(!empty($resource['restricted_to_niveaux']) && strpos($resource['restricted_to_niveaux'], $user_niveau) !== false) {
        $can_access = true;
    }
    if(!empty($resource['restricted_to_groupes']) && strpos($resource['restricted_to_groupes'], $user_groupe) !== false) {
        $can_access = true;
    }
}

if(!$can_access) {
    die('Accès refusé à cette ressource');
}

// Enregistrer la vue
$viewStmt = mysqli_prepare($conn, "INSERT INTO views(res_id, user_id) VALUES(?, ?)");
mysqli_stmt_bind_param($viewStmt, 'ii', $id, $user_id);
mysqli_stmt_execute($viewStmt);

// Compter les vues et téléchargements
$view_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM views WHERE res_id=$id"))['c'];
$download_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM downloads WHERE res_id=$id"))['c'];

// Récupérer les ressources similaires (même module, tags communs)
$similar_query = "SELECT r.id, r.titre, r.type, (SELECT COUNT(*) FROM downloads WHERE res_id=r.id) as downloads 
                  FROM resources r 
                  WHERE r.id != $id AND r.visibility='Public' 
                  AND (r.module='{$resource['module']}' OR r.tags LIKE '%{$resource['tags']}%')
                  LIMIT 5";
$similar_resources = [];
$res = mysqli_query($conn, $similar_query);
while($r = mysqli_fetch_assoc($res)) {
    $similar_resources[] = $r;
}

// Récupérer les téléchargements récents
$recent_downloads = [];
$res = mysqli_query($conn, "SELECT username, downloaded_at FROM downloads WHERE res_id=$id ORDER BY downloaded_at DESC LIMIT 10");
while($r = mysqli_fetch_assoc($res)) {
    $recent_downloads[] = $r;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($resource['titre'])?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">

<div class="resource-details">
    <div class="resource-header-full">
        <h1><?=htmlspecialchars($resource['titre'])?></h1>
        <div class="resource-badges">
            <span class="badge badge-type"><?=$resource['type']?></span>
            <span class="badge badge-level"><?=$resource['niveau']?></span>
            <span class="badge badge-module"><?=$resource['module']?></span>
        </div>
    </div>

    <div class="resource-info">
        <div class="info-group">
            <strong>Enseignant:</strong> <?=htmlspecialchars($resource['username'])?>
        </div>
        <div class="info-group">
            <strong>Publié le:</strong> <?=date('d/m/Y à H:i', strtotime($resource['date_add']))?>
        </div>
        <?php if($resource['date_update'] !== $resource['date_add']): ?>
        <div class="info-group">
            <strong>Mise à jour:</strong> <?=date('d/m/Y à H:i', strtotime($resource['date_update']))?>
        </div>
        <?php endif; ?>
    </div>

    <div class="resource-body">
        <div class="description-section">
            <h3>📄 Description</h3>
            <p><?=nl2br(htmlspecialchars($resource['description']))?></p>
        </div>

        <?php if(!empty($resource['tags'])): ?>
        <div class="tags-section">
            <h3>🏷️ Tags</h3>
            <div class="tags-list">
                <?php foreach(array_filter(explode(',', $resource['tags'])) as $tag): ?>
                <a href="search.php?tags=<?=urlencode(trim($tag))?>" class="tag-link"><?=htmlspecialchars(trim($tag))?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="stats-section">
            <h3>📊 Statistiques</h3>
            <div class="stats-grid">
                <div class="stat">
                    <div class="stat-value"><?=$view_count?></div>
                    <div class="stat-label">Vues</div>
                </div>
                <div class="stat">
                    <div class="stat-value"><?=$download_count?></div>
                    <div class="stat-label">Téléchargements</div>
                </div>
            </div>
        </div>

        <div class="action-section">
            <a href="download.php?id=<?=$id?>" class="btn btn-primary btn-large">⬇️ Télécharger</a>
            <a href="search.php" class="btn btn-secondary">← Retour à la recherche</a>
        </div>
    </div>

    <?php if(!empty($similar_resources)): ?>
    <div class="similar-section">
        <h3>📚 Ressources similaires</h3>
        <div class="similar-list">
            <?php foreach($similar_resources as $sim): ?>
            <div class="similar-item">
                <a href="view.php?id=<?=$sim['id']?>"><?=htmlspecialchars($sim['titre'])?></a>
                <span class="type-badge"><?=$sim['type']?></span>
                <span class="download-count">⬇️ <?=$sim['downloads']?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!empty($recent_downloads)): ?>
    <div class="downloads-section">
        <h3>📥 Téléchargements récents</h3>
        <table class="downloads-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_downloads as $dl): ?>
                <tr>
                    <td><?=htmlspecialchars($dl['username'])?></td>
                    <td><?=date('d/m/Y H:i', strtotime($dl['downloaded_at']))?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

</div>

<script src="assets/app.js"></script>
</body>
</html>
