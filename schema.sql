-- Création de la base de données
CREATE DATABASE IF NOT EXISTS projet2;
USE projet2;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') DEFAULT 'student',
    niveau VARCHAR(50),
    groupe VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des modules
CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des ressources
CREATE TABLE IF NOT EXISTS resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    type ENUM('Cours', 'TP', 'Examen', 'Corrigé', 'Autre') NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    fichier VARCHAR(255) NOT NULL,
    module VARCHAR(100),
    niveau VARCHAR(50),
    tags VARCHAR(500),
    visibility ENUM('Public', 'Privé', 'Restreint') DEFAULT 'Public',
    restricted_to_niveaux VARCHAR(500),
    restricted_to_groupes VARCHAR(500),
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_teacher (teacher_id),
    INDEX idx_visibility (visibility),
    INDEX idx_date (date_add)
);

-- Table des vues (tracking)
CREATE TABLE IF NOT EXISTS views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    res_id INT NOT NULL,
    user_id INT,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (res_id) REFERENCES resources(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_resource (res_id),
    INDEX idx_user (user_id)
);

-- Table des téléchargements (tracking)
CREATE TABLE IF NOT EXISTS downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    res_id INT NOT NULL,
    user_id INT,
    username VARCHAR(100),
    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (res_id) REFERENCES resources(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_resource (res_id),
    INDEX idx_user (user_id),
    INDEX idx_date (downloaded_at)
);

-- Table de liaison ressources-tags
CREATE TABLE IF NOT EXISTS tags_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    res_id INT NOT NULL,
    tag VARCHAR(100) NOT NULL,
    FOREIGN KEY (res_id) REFERENCES resources(id) ON DELETE CASCADE,
    INDEX idx_resource (res_id),
    INDEX idx_tag (tag)
);

-- Insertion de modules par défaut
INSERT INTO modules (nom) VALUES 
('Informatique'),
('Mathématiques'),
('Physique'),
('Chimie'),
('Langues'),
('Économie'),
('Droit');
