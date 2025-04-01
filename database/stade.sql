drop database if exists stade;
create database stade;
use stade;

CREATE TABLE utilisateurs (
    idUtilisateur INT PRIMARY KEY AUTO_INCREMENT,
    pseudonyme VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    dateNaissance DATE,
    sexe ENUM('Homme', 'Femme', 'Autre'),
    typeMembre ENUM('spectateur', 'athlète', 'entraîneur', 'sécurité', 'administratif'),
    photo VARCHAR(255),
    niveau ENUM('débutant', 'intermédiaire', 'avancé', 'expert') DEFAULT 'débutant',
    points INT DEFAULT 0,
    dateInscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE evenements (
    idEvenement INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    dateEvenement DATETIME NOT NULL,
    equipe1 VARCHAR(50),
    equipe2 VARCHAR(50),
    stade VARCHAR(100),
    disponibiliteBillets INT,
    prix DECIMAL(5,2),
    descriptionEvenement TEXT
);

CREATE TABLE objetsConnectes (
    idObjet INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    typeObjet ENUM('capteur', 'éclairage', 'écran', 'climatisation', 'caméra', 'autre'),
    descriptionObjet TEXT,
    connectivite ENUM('Wi-Fi', 'Ethernet', 'Bluetooth'),
    etat ENUM('actif', 'en panne', 'maintenance') DEFAULT 'actif',
    derniereInteraction DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    idService INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    descriptionService TEXT,
    typeService ENUM('restauration', 'billetterie', 'boutique', 'orientation', 'sécurité'),
    disponibilite BOOLEAN DEFAULT true
);

INSERT INTO utilisateurs (pseudonyme, email, mdp, nom, prenom, dateNaissance, sexe, typeMembre, photo, niveau, points, dateInscription) 
VALUES ('test', 'test', 'test', 'test', 'test', '2004-02-02', 'Femme', 'spectateur', 'photo', DEFAULT, DEFAULT, DEFAULT);
INSERT INTO evenements (nom, dateEvenement, equipe1, equipe2, stade, disponibiliteBillets, prix, descriptionEvenement) 
VALUES ('test', '2025-03-03 21:00:00', 'test', 'test', 'test', 50, 14, 'long texte pour tester');
INSERT INTO objetsConnectes (nom, typeObjet, descriptionObjet, connectivite, etat, derniereInteraction) 
VALUES ('test', 'capteur', 'test', 'Ethernet', DEFAULT, DEFAULT);
INSERT INTO services (nom, descriptionService, typeService, disponibilite) 
VALUES ('test', 'test', 'sécurité', DEFAULT);
SELECT * FROM utilisateurs;
SELECT * FROM evenements;
SELECT * FROM objetsConnectes;
SELECT idService, nom, descriptionService, typeService, IF(disponibilite, 'true', 'false') AS disponibilite
FROM services;
