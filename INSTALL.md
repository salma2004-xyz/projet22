# 🚀 Guide de Démarrage Rapide

## Installation Rapide (5 minutes)

### Étape 1: Préparation
```bash
# Accédez au dossier XAMPP
cd C:\xampp\htdocs\projet2

# Vérifiez que les dossiers existent
# - assets/
# - inc/
# - ressources/ (créé automatiquement)
```

### Étape 2: Créer la Base de Données

**Option A - Via Ligne de Commande (CMD)**
```bash
mysql -u root < schema.sql
```

**Option B - Via phpMyAdmin**
1. Ouvrez `http://localhost/phpmyadmin`
2. Cliquez sur "Importer"
3. Sélectionnez `schema.sql`
4. Cliquez sur "Exécuter"

### Étape 3: Vérifier la Configuration
Ouvrez `inc/db.php` et vérifiez:
```php
$conn = mysqli_connect('localhost', 'root', '', 'projet2');
```

Si vous avez changé les paramètres MySQL, mettez à jour ici.

### Étape 4: Démarrer XAMPP
```bash
# Windows - Démarrer XAMPP Control Panel
# Cliquez sur "Start" pour Apache et MySQL

# Linux/Mac
sudo /Applications/XAMPP/xamppfiles/bin/xampp start
```

### Étape 5: Accéder à l'Application
```
http://localhost/projet2/
```

## 📝 Premiers Pas

### 1. Créer un Compte Admin

**Option 1 - Via Inscription**
1. Cliquez sur "S'inscrire"
2. Username: `admin`
3. Password: `admin123`
4. Role: **Administrateur**
5. Validez

**Option 2 - Via Base de Données**
```sql
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$...hash...', 'admin');
```

### 2. Créer un Compte Enseignant
1. S'inscrire avec:
   - Username: `teacher1`
   - Password: `pass123`
   - Role: **Enseignant**
2. Validez

### 3. Créer un Compte Étudiant
1. S'inscrire avec:
   - Username: `student1`
   - Password: `pass123`
   - Role: **Étudiant**
   - Niveau: **CP1** ou autre
   - Groupe: **Groupe 1** ou autre
2. Validez

## ✅ Test des Fonctionnalités

### Test 1: Upload de Ressource (Enseignant)
```
1. Se connecter avec teacher1
2. Cliquer sur "Uploader"
3. Remplir le formulaire:
   - Type: Cours
   - Titre: "Bases de Données"
   - Description: "Introduction SQL"
   - Module: Informatique
   - Niveau: CP1
   - Tags: SQL, Bases de Données
   - Fichier: Créez un PDF test
   - Visibilité: Public
4. Cliquez "Uploader"
5. Allez sur "Mes Ressources" pour voir les stats
```

### Test 2: Recherche de Ressource (Étudiant)
```
1. Se connecter avec student1
2. Cliquer sur "Ressources"
3. Rechercher "SQL"
4. Filtrer par Module: Informatique
5. Trier par: Plus récent
6. Cliquer sur la ressource
7. Cliquer "Télécharger"
8. Vérifier le fichier dans Téléchargements
```

### Test 3: Modification de Ressource
```
1. Se connecter avec teacher1
2. Aller sur "Mes Ressources"
3. Cliquer sur "Edit" à côté de la ressource
4. Modifier le titre
5. Cliquer "Enregistrer"
6. Vérifier que les changes sont appliquées
```

### Test 4: Gestion des Droits
```
1. Enseignant upload une ressource
2. Visibilité: Restreint
3. Sélectionnez: CP2 uniquement
4. Connectez-vous avec student1 (CP1)
5. Cherchez la ressource - elle ne doit pas apparaître
6. Créez un étudiant CP2 et reconnectez
7. La ressource doit maintenant être visible
```

## 🐛 Dépannage Courant

### "Connexion à la base de données impossible"
```bash
# Vérifier que MySQL est lancé
# Windows: XAMPP Control Panel -> Start pour MySQL
# Linux: sudo service mysql start
# Mac: /Applications/XAMPP/xamppfiles/bin/mysql.server start

# Vérifier les identifiants dans inc/db.php
# Tester la connexion:
mysql -u root -p
```

### "Erreur 404 Page non trouvée"
```bash
# Vérifier l'URL
# Doit être: http://localhost/projet2/ (NOT c:\xampp\htdocs\projet2\)

# Vérifier que tous les fichiers existent
# Vérifier les droits d'accès des dossiers
chmod 755 assets/ ressources/ inc/
```

### "Permission denied - Upload échoue"
```bash
# Créer le dossier ressources s'il n'existe pas
mkdir ressources/

# Donner les permissions
chmod 755 ressources/
chmod 777 ressources/  # Si 755 ne fonctionne pas

# Linux - Changer le propriétaire
sudo chown www-data:www-data ressources/
```

### "Fichier trop volumineux"
```
# Augmenter les limites dans php.ini
# Cherchez:
upload_max_filesize = 50M
post_max_size = 50M

# Redémarrez Apache
```

### "Session ne se crée pas"
```
# Vérifier que session_start() est au début de chaque page
# Vérifier que le dossier /tmp existe et est writable
# Ou créer un dossier sessions et changer php.ini:
session.save_path = "/path/to/sessions"
```

## 📂 Structure des Fichiers Important

```
projet2/
├── inc/db.php          ⚠️ Important! Vérifier la connexion MySQL
├── schema.sql          📋 Importer d'abord dans MySQL
├── ressources/         📁 Créé automatiquement (doit être writable)
├── assets/style.css    🎨 Feuille de styles (500+ lignes)
└── index.php           🏠 Page d'accueil
```

## 🔐 Sécurité de Déve

Pour le développement local, les paramètres par défaut sont:
- Database: `projet2`
- User: `root`
- Password: (vide)
- Host: `localhost`

**⚠️ IMPORTANT**: En production, changez le mot de passe root et créez un utilisateur dédié!

## 📊 Fichiers de Configuration

### inc/db.php
```php
$conn = mysqli_connect(
    'localhost',    // Serveur MySQL
    'root',         // Utilisateur
    '',             // Mot de passe (vide en dev)
    'projet2'       // Base de données
);
```

### php.ini (Limites)
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

## 🌐 URLs Utiles

```
http://localhost/projet2/              # Page d'accueil
http://localhost/projet2/index.php     # Login
http://localhost/projet2/register.php  # Inscription
http://localhost/projet2/search.php    # Recherche
http://localhost/phpmyadmin            # Admin base de données
```

## 📞 Support et Aide

### Vérifier les logs
```bash
# Log Apache
tail -f /var/log/apache2/error.log

# Log MySQL
tail -f /var/log/mysql/error.log

# Log PHP
tail -f /var/log/php-errors.log
```

### Activer les messages d'erreur (développement)
Dans `inc/db.php`, ajoutez:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Tester une requête SQL
```bash
mysql -u root projet2 < schema.sql
mysql -u root projet2 -e "SELECT * FROM users;"
```

## ✨ Prochaines Étapes

Après avoir testé les fonctionnalités:

1. **Ajouter du contenu**
   - Créer plusieurs ressources
   - Tester les filtres
   - Générer des données de test

2. **Personnaliser**
   - Modifier `assets/style.css`
   - Ajouter votre logo
   - Changer les couleurs

3. **Étendre**
   - Ajouter des modules
   - Créer des catégories
   - Implémenter des graphiques

4. **Déployer**
   - Configurer un serveur production
   - Mettre en place HTTPS
   - Configurer les sauvegardes

## 🎓 Exercices Recommandés

1. **Ajouter un système de tags auto-complétion**
2. **Implémenter la pagination des résultats**
3. **Créer un système de favoris**
4. **Ajouter des notifications email**
5. **Générer des rapports PDF**

---

**Temps d'installation total**: ~5 minutes
**Temps de test**: ~20 minutes

Vous êtes prêt à démarrer! 🚀
