<?php
// Barre de navigation
?>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="index.php">📚 Plateforme Pédagogique</a>
        </div>
        <ul class="nav-menu">
            <?php if(isset($_SESSION['user'])): ?>
                <li><a href="index.php">Accueil</a></li>
                <?php if($_SESSION['user']['role'] === 'student'): ?>
                    <li><a href="search.php">Ressources</a></li>
                    <li><a href="dashboard_student.php">Mon Dashboard</a></li>
                <?php elseif($_SESSION['user']['role'] === 'teacher'): ?>
                    <li><a href="search.php">Consulter</a></li>
                    <li><a href="upload.php">Uploader</a></li>
                    <li><a href="dashboard_teacher.php">Mes Ressources</a></li>
                <?php elseif($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a href="dashboard_admin.php">Administration</a></li>
                    <li><a href="manage_users.php">Utilisateurs</a></li>
                <?php endif; ?>
                <li class="nav-user">
                    <span><?=htmlspecialchars($_SESSION['user']['username'])?></span>
                    <a href="logout.php" class="btn-logout">Déconnexion</a>
                </li>
            <?php else: ?>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#login" class="btn-login">Connexion</a></li>
                <li><a href="register.php" class="btn-register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
