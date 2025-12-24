<?php
session_start();
require_once 'inc/db.php';
requiresLogin();

// Paramètres de recherche
$keyword = $_GET['q'] ?? '';
$type = $_GET['type'] ?? '';
$module = $_GET['module'] ?? '';
$niveau = $_GET['niveau'] ?? '';
$tags = $_GET['tags'] ?? '';
$sort = $_GET['sort'] ?? 'recent';

// Construire la requête WHERE
$where = ["visibility='Public'"];

// Gestion des droits d'accès restreints
if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'student') {
    $student_niveau = $_SESSION['user']['niveau'];
    $student_groupe = $_SESSION['user']['groupe'];
    
    $where[] = "(visibility='Public' OR 
                (visibility='Restreint' AND (
                    restricted_to_niveaux LIKE '%$student_niveau%' OR
                    restricted_to_groupes LIKE '%$student_groupe%'
                )))";
}

if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $where[] = "(titre LIKE '%$keyword%' OR description LIKE '%$keyword%' OR tags LIKE '%$keyword%')";
}

if (!empty($type)) {
    $type = mysqli_real_escape_string($conn, $type);
    $where[] = "type='$type'";
}

if (!empty($module)) {
    $module = mysqli_real_escape_string($conn, $module);
    $where[] = "module='$module'";
}

if (!empty($niveau)) {
    $niveau = mysqli_real_escape_string($conn, $niveau);
    $where[] = "niveau='$niveau'";
}

if (!empty($tags)) {
    $tags_escaped = mysqli_real_escape_string($conn, $tags);
    $where[] = "tags LIKE '%$tags_escaped%'";
}

// Tri
$orderBy = "ORDER BY date_add DESC";
if ($sort === 'popular') {
    $orderBy = "ORDER BY (SELECT COUNT(*) FROM downloads WHERE downloads.res_id=resources.id) DESC";
} elseif ($sort === 'alphabetic') {
    $orderBy = "ORDER BY titre ASC";
}

$whereStr = implode(" AND ", $where);
$query = "SELECT r.*, u.username, (SELECT COUNT(*) FROM downloads WHERE res_id=r.id) as download_count, (SELECT COUNT(*) FROM views WHERE res_id=r.id) as view_count 
          FROM resources r 
          JOIN users u ON r.teacher_id=u.id 
          WHERE $whereStr 
          $orderBy";

$result = mysqli_query($conn, $query);
$resources = [];
while($row = mysqli_fetch_assoc($result)) {
    $resources[] = $row;
}

// Récupérer les options pour les filtres
$types = ['Cours', 'TP', 'Examen', 'Corrigé', 'Autre'];
$modules = [];
$res = mysqli_query($conn, "SELECT DISTINCT module FROM resources WHERE module IS NOT NULL ORDER BY module");
while($r = mysqli_fetch_assoc($res)) $modules[] = $r['module'];

$niveaux = ['CP1', 'CP2', 'CI1', 'CI2', 'CI3'];

$all_tags = [];
$res = mysqli_query($conn, "SELECT DISTINCT tag FROM tags_resources ORDER BY tag");
while($r = mysqli_fetch_assoc($res)) $all_tags[] = $r['tag'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rechercher des ressources</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
<h1>🔍 Rechercher des ressources</h1>

<div class="search-container">
    <form method="get" action="search.php" class="search-form">
        <div class="search-box">
            <input type="text" name="q" value="<?=htmlspecialchars($keyword)?>" placeholder="Rechercher par mot-clé...">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>

        <div class="filters">
            <h3>Filtres</h3>
            
            <div class="filter-group">
                <label>Type</label>
                <select name="type">
                    <option value="">Tous les types</option>
                    <?php foreach($types as $t): ?>
                    <option value="<?=$t?>" <?=$type===$t?'selected':''?>><?=$t?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Module</label>
                <select name="module">
                    <option value="">Tous les modules</option>
                    <?php foreach($modules as $m): ?>
                    <option value="<?=htmlspecialchars($m)?>" <?=$module===$m?'selected':''?>><?=htmlspecialchars($m)?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Niveau</label>
                <select name="niveau">
                    <option value="">Tous les niveaux</option>
                    <?php foreach($niveaux as $n): ?>
                    <option value="<?=$n?>" <?=$niveau===$n?'selected':''?>><?=$n?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Tri</label>
                <select name="sort">
                    <option value="recent" <?=$sort==='recent'?'selected':''?>>Plus récent</option>
                    <option value="popular" <?=$sort==='popular'?'selected':''?>>Plus téléchargé</option>
                    <option value="alphabetic" <?=$sort==='alphabetic'?'selected':''?>>Alphabétique</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
            <a href="search.php" class="btn btn-secondary">Réinitialiser</a>
        </div>
    </form>
</div>

<div class="results-section">
    <h2>Résultats (<?=count($resources)?> ressource<?=count($resources)!==1?'s':''?>)</h2>

    <?php if(empty($resources)): ?>
        <p class="no-results">Aucune ressource ne correspond à vos critères.</p>
    <?php else: ?>
        <div class="resources-list">
            <?php foreach($resources as $res): ?>
            <div class="resource-card">
                <div class="resource-header">
                    <h3><a href="view.php?id=<?=$res['id']?>"><?=htmlspecialchars($res['titre'])?></a></h3>
                    <span class="resource-type"><?=$res['type']?></span>
                </div>
                
                <p class="resource-description"><?=substr(htmlspecialchars($res['description']), 0, 150)?>...</p>
                
                <div class="resource-meta">
                    <span>👨‍🏫 <?=htmlspecialchars($res['username'])?></span>
                    <span>📚 <?=htmlspecialchars($res['module'])?></span>
                    <span>📅 <?=date('d/m/Y', strtotime($res['date_add']))?></span>
                </div>

                <div class="resource-stats">
                    <span>👁️ <?=$res['view_count']?> vues</span>
                    <span>⬇️ <?=$res['download_count']?> téléchargements</span>
                </div>

                <?php if(!empty($res['tags'])): ?>
                <div class="resource-tags">
                    <?php foreach(array_filter(explode(',', $res['tags'])) as $tag): ?>
                    <a href="search.php?tags=<?=urlencode(trim($tag))?>" class="tag"><?=htmlspecialchars(trim($tag))?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="resource-actions">
                    <a href="view.php?id=<?=$res['id']?>" class="btn btn-small">Consulter</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</div>

<script src="assets/app.js"></script>
</body>
</html>
