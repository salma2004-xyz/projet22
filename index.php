<?php
session_start();
require_once 'inc/db.php';

// Si déjà connecté, rediriger vers dashboard approprié
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role === 'admin') header('Location: dashboard_admin.php');
    elseif ($role === 'teacher') header('Location: dashboard_teacher.php');
    elseif ($role === 'student') header('Location: dashboard_student.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (loginUser($username, $password)) {
        $role = $_SESSION['user']['role'];
        if ($role === 'admin') header('Location: dashboard_admin.php');
        elseif ($role === 'teacher') header('Location: dashboard_teacher.php');
        elseif ($role === 'student') header('Location: dashboard_student.php');
        exit;
    } else {
        $error = 'Identifiants invalides';
    }
}

// Récupérer les statistiques publiques
$totalResources = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM resources WHERE visibility='Public'"))['c'] ?? 0;
$totalTeachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT teacher_id) as c FROM resources"))['c'] ?? 0;
$totalDownloads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM downloads"))['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de Gestion des Ressources Pédagogiques</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>📚 Plateforme de Gestion des Ressources Pédagogiques</h1>
            <p>Accédez à des cours, TPs, examens et ressources pédagogiques</p>
            <?php if(!isset($_SESSION['user'])): ?>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary">S'inscrire</a>
                    <a href="#login" class="btn btn-secondary">Se connecter</a>
                </div>
            <?php else: ?>
                <div class="hero-buttons">
                    <a href="search.php" class="btn btn-primary">Consulter les ressources</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Statistiques -->
    <section class="stats">
        <div class="stat-card">
            <h3><?=$totalResources?></h3>
            <p>Ressources publiques</p>
        </div>
        <div class="stat-card">
            <h3><?=$totalTeachers?></h3>
            <p>Enseignants contributeurs</p>
        </div>
        <div class="stat-card">
            <h3><?=$totalDownloads?></h3>
            <p>Téléchargements</p>
        </div>
    </section>

    <!-- Section Login -->
    <?php if(!isset($_SESSION['user'])): ?>
    <section id="login" class="auth-section">
        <div class="container">
            <div class="auth-container">
                <h2>Connexion</h2>
                <?php if ($error) echo "<p class='error'>".htmlspecialchars($error)."</p>"; ?>
                <form method="post" action="index.php">
                    <div class="form-group">
                        <label>Nom d'utilisateur</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
                <p class="auth-link">Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Plateforme Pédagogique - Tous droits réservés</p>
    </footer>

    <script src="assets/app.js"></script>
</body>
</html>
