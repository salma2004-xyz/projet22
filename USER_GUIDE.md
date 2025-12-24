# 📘 Guide Complet d'Utilisation

## 🎯 Pour les Étudiants

### Inscription
1. Cliquez sur "S'inscrire" depuis la page d'accueil
2. Remplissez le formulaire:
   - **Nom d'utilisateur**: Identifiant unique
   - **Mot de passe**: Au moins 6 caractères
   - **Rôle**: Sélectionnez "Étudiant"
   - **Niveau**: CP1, CP2, CI1, CI2 ou CI3
   - **Groupe**: Groupe 1 à 5
3. Cliquez "S'inscrire"

### Connexion
1. Allez sur la page d'accueil
2. Scroll jusqu'à la section "Connexion"
3. Entrez vos identifiants
4. Cliquez "Se connecter"

### Rechercher une Ressource
1. Cliquez sur "Ressources" dans le menu
2. **Recherche par mot-clé**:
   - Tapez dans la barre de recherche
   - Cherche dans: titre, description, tags
   - Cliquez "Rechercher" ou appuyez Enter
3. **Utiliser les filtres**:
   - Type: Cours, TP, Examen, etc.
   - Module: Informatique, Maths, etc.
   - Niveau: CP1, CP2, etc.
   - Tri: Récent, Populaire, Alphabétique
4. Les résultats s'affichent avec:
   - Titre et auteur
   - Nombre de vues et téléchargements
   - Tags cliquables
   - Date d'ajout

### Consulter une Ressource
1. Cliquez sur une ressource dans les résultats
2. Consultez les détails:
   - **Description complète**
   - **Tags** - Cliquez pour rechercher similaires
   - **Auteur** - Enseignant qui a partagé
   - **Statistiques** - Vues et téléchargements
   - **Ressources similaires** - Même module ou tags
   - **Téléchargements récents** - Historique

### Télécharger une Ressource
1. Depuis la page de détails
2. Cliquez le bouton "⬇️ Télécharger"
3. Le fichier se télécharge automatiquement
4. Après téléchargement:
   - Votre nom apparait dans l'historique
   - Le compteur augmente automatiquement
   - Vous pouvez voir le fichier en local

### Comprendre les Droits d'Accès

**Public** 🌍
- Tous les étudiants connectés peuvent voir et télécharger

**Privé** 🔐
- Seul l'auteur peut voir (personne d'autre)

**Restreint** 🔒
- Accessible uniquement à:
  - Certains niveaux (ex: CP2 seulement)
  - Certains groupes (ex: Groupe 5 seulement)
- Vous voyez la ressource que si votre niveau/groupe correspond

### Conseils de Recherche
- Recherchez par concepts clés
- Utilisez les tags pour des sujets précis
- Triez par "Populaire" pour les ressources fiables
- Les ressources récentes sont à jour
- Les ressources avec beaucoup de téléchargements sont utiles

### Questions Fréquentes
**Q: Pourquoi je ne vois pas une ressource?**
A: Elle est peut-être privée ou restreinte à un autre niveau/groupe

**Q: Comment voir les ressources d'un enseignant?**
A: Cherchez par module, puis filtrez

**Q: Puis-je commenter une ressource?**
A: Actuellement non, mais c'est planifié pour le futur

---

## 👨‍🏫 Pour les Enseignants

### Inscription
1. Cliquez sur "S'inscrire"
2. Remplissez le formulaire:
   - **Nom d'utilisateur**: Identifiant unique
   - **Mot de passe**: Sécurisé
   - **Rôle**: Sélectionnez "Enseignant"
   - **Niveau**: Optionnel (votre formation)
   - **Groupe**: Optionnel
3. Cliquez "S'inscrire"

### Créer un Compte Admin (Installation)
```php
// Exécutez cette requête MySQL:
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$...hash...', 'admin');
```

### Upload d'une Ressource
1. Cliquez sur "Uploader" dans le menu
2. Remplissez le formulaire:

#### Section 1: Informations Générales
- **Type** ⭐: Cours, TP, Examen, Corrigé, Autre
- **Titre** ⭐: "Introduction aux Bases de Données"
- **Description**: Contenu, objectifs, prérequis

#### Section 2: Classification
- **Module** ⭐: Informatique, Maths, etc.
- **Niveau** ⭐: CP1, CP2, CI1, CI2, CI3
- **Tags**: Mots-clés séparés par des virgules
  - Exemple: "SQL, Normalisation, Relationnel"
  - Aide les étudiants à trouver votre ressource

#### Section 3: Fichier
- **Fichier** ⭐: PDF, DOCX, PPT, ZIP (Max 50MB)
  - Formats supportés:
    - PDF: Cours, examens
    - DOCX: Documents Word
    - PPT/PPTX: Présentations
    - ZIP: Archives de plusieurs fichiers

#### Section 4: Droits d'Accès
- **Public**: Tous les étudiants voient et téléchargent
- **Privé**: Personne ne voit (même les admins)
- **Restreint**: Sélectionnez:
  - Niveaux autorisés (checkbox)
  - Groupes autorisés (checkbox)
  - Combinaison: Les deux conditions s'ajoutent (OU logique)

3. Cliquez "📤 Uploader la ressource"
4. Confirmation: "Ressource uploadée avec succès"

### Gérer vos Ressources
1. Cliquez sur "Mes Ressources"
2. Tableau avec toutes vos ressources:

#### Colonnes
- **Titre**: Nom de la ressource
- **Type**: Cours, TP, etc.
- **Module**: Catégorie
- **Vues**: Nombre de consultations
- **Téléchargements**: Nombre de téléchargements
- **Actions**: 
  - **Voir**: Ouvre la page publique
  - **Edit**: Modifie la ressource
  - **Suppr**: Supprime définitivement

### Modifier une Ressource
1. Cliquez sur "Edit" à côté de la ressource
2. Modifiez:
   - Titre, description, tags
   - Visibilité et droits d'accès
   - **Remplacer le fichier**: Sélectionnez un nouveau
3. L'ancien fichier est supprimé automatiquement
4. Cliquez "Enregistrer"

### Supprimer une Ressource
1. Cliquez sur "Suppr" à côté de la ressource
2. Confirmation: "Êtes-vous sûr?"
3. Cliquez "Oui"
4. Le fichier et les données sont supprimés

### Dashboard Enseignant
Affiche pour chaque ressource:
- Nombre de vues (consultations)
- Nombre de téléchargements
- Graphique: Téléchargements par mois (derniers 6 mois)
- Historique: Derniers téléchargements avec nom étudiant + date

### Bonnes Pratiques
1. **Titres clairs**: "SQL - Normalisation" plutôt que "Doc1"
2. **Descriptions détaillées**: Résumé + objectifs + prérequis
3. **Tags pertinents**: Facilitent la découverte
4. **Fichiers nettoyés**: Vérifier avant d'uploader
5. **Droits appropriés**: Public si utile à tous, Restreint sinon
6. **Mise à jour**: Modifiez régulièrement les contenus

### Conseils pour les Uploads
- ✅ Une ressource = un sujet spécifique
- ✅ Nommez clairement vos fichiers
- ✅ Utilisez des PDFs pour les documents finalisés
- ✅ Utilisez les ZIP pour grouper plusieurs fichiers
- ✅ Mettez à jour les vieux contenus
- ❌ N'uploadez pas de doublons
- ❌ N'uploadez pas des fichiers corrompus

---

## 👨‍💼 Pour les Administrateurs

### Accès Admin
1. Se connecter avec compte admin
2. Menu spécial "Administration"
3. Cliquez sur "Administration"

### Dashboard Admin
Affiche les statistiques globales:
- **Ressources par type**: Cours, TP, Examen, etc.
- **Top 10 ressources**: Plus téléchargées
- **Top 5 enseignants**: Plus contributifs
- **Graphique mensuel**: Évolution des uploads
- **Statistiques par module**: Répartition des ressources

### Gérer les Utilisateurs (Planifié)
- [x] Liste des utilisateurs
- [ ] Créer un nouvel utilisateur
- [ ] Modifier un utilisateur
- [ ] Désactiver un utilisateur
- [ ] Réinitialiser mot de passe

### Gérer les Modules (Planifié)
- [ ] Ajouter un module
- [ ] Renommer un module
- [ ] Supprimer un module
- [ ] Archiver un module

### Modérer les Ressources
- [x] Voir toutes les ressources
- [ ] Supprimer une ressource (si contenu inapproprié)
- [ ] Signaler les contenus
- [ ] Archiver les anciennes ressources

### Générer des Rapports
- Nombre d'utilisateurs par type
- Ressources créées par mois
- Utilisation par module
- Taux de téléchargement

### Paramètres du Site (Planifié)
- [ ] Nom du site
- [ ] Email de contact
- [ ] Logo de l'établissement
- [ ] Limites de taille de fichier
- [ ] Formats autorisés

---

## 🔐 Sécurité et Confidentialité

### Mots de Passe
- Minimum 6 caractères (recommandé 10+)
- Stockés en hash sécurisé (ne jamais stockés en clair)
- Ne partagez jamais votre mot de passe
- Changez-le régulièrement

### Fichiers
- Vérifiés avant téléchargement
- Stockés dans un dossier sécurisé
- Pas d'exécution de code
- Accès contrôlé par les droits

### Comptes
- Une adresse email = Un compte
- Les admins ne voient pas les mots de passe
- Les sessions expirent après 1 heure d'inactivité
- Déconnexion sécurisée

### Données
- Les téléchargements sont enregistrés (mais anonymes)
- Les admins peuvent voir qui a téléchargé
- Les données ne sont pas vendues
- Respect de la confidentialité

---

## 🆘 Support et Assistance

### Problèmes Courants

**"Je ne peux pas me connecter"**
- Vérifiez votre username
- Vérifiez votre mot de passe (minuscules/majuscules)
- Le compte a-t-il été créé?

**"Le fichier ne télécharge pas"**
- La ressource existe-t-elle?
- Avez-vous les droits d'accès?
- Votre navigateur bloque-t-il les téléchargements?

**"Je ne vois pas ma ressource"**
- Vérifiez que vous êtes connecté
- Allez sur "Mes Ressources"
- Vérifiez la visibilité (Public/Restreint)

**"Erreur d'upload du fichier"**
- Le fichier est-il au bon format? (PDF, DOCX, PPT, ZIP)
- La taille est-elle < 50MB?
- Avez-vous l'espace disque?

### Contacter l'Admin
- Email: admin@example.com
- Rapport d'erreur: Décrivez le problème précisément

### Feedback et Suggestions
- Aider à améliorer la plateforme
- Proposer nouvelles fonctionnalités
- Signaler les bugs

---

## 📊 Statistiques Personnelles

### Étudiants
- Nombre de ressources consultées
- Nombre de ressources téléchargées
- Ressources favorisées
- Historique de recherche

### Enseignants
- Nombre total de ressources créées
- Nombre total de vues
- Nombre total de téléchargements
- Ressource la plus populaire
- Module le plus chargé

### Admin
- Nombre total d'utilisateurs (par type)
- Nombre total de ressources
- Taille totale des fichiers
- Activité mensuelle

---

## 🎓 Bonnes Pratiques

### Pour Tous
1. ✅ Utilisez des mots de passe forts
2. ✅ Gardez vos informations à jour
3. ✅ Signalez les problèmes rapidement
4. ✅ Respectez les droits d'auteur
5. ✅ Téléchargez régulièrement vos fichiers

### Spécifiques aux Enseignants
1. ✅ Organisez vos ressources logiquement
2. ✅ Décrivez clairement le contenu
3. ✅ Utilisez les tags correctement
4. ✅ Mettez à jour régulièrement
5. ✅ Validez les fichiers avant upload

### Spécifiques aux Étudiants
1. ✅ Lisez les descriptions complètes
2. ✅ Cherchez par mots-clés pertinents
3. ✅ Utilisez les filtres efficacement
4. ✅ Signalez les ressources défectueuses
5. ✅ Partagez vos ressources préférées

---

## 📝 Notes

- Cette plateforme est en développement continu
- De nouvelles fonctionnalités arrivent régulièrement
- Votre feedback est précieux
- Respectez les conditions d'utilisation

**Version**: 1.0
**Dernière mise à jour**: Novembre 2025
**Support**: admin@example.com

