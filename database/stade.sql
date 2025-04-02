drop database if exists stade;
create database stade;
use stade;

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    idUtilisateurs INT PRIMARY KEY AUTO_INCREMENT,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    dateNaissance DATE,
    age INT,
    sexe ENUM('Homme', 'Femme', 'Autre'),
    typeMembre ENUM('Spectateur', 'Athlète', 'Entraîneur', 'Personnel technique', 'Sécurité', 'Administratif'),
    niveau ENUM('Débutant', 'Intermédiaire', 'Avancé', 'Expert') DEFAULT 'Débutant',
    points FLOAT DEFAULT 0,
    photo VARCHAR(255),
    dateInscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    derniereConnexion DATETIME,
    estActif BOOLEAN DEFAULT TRUE,
    estVerifie BOOLEAN DEFAULT FALSE
);

-- Table des niveaux de competences
CREATE TABLE niveauxCompetences (
    idNiveauxCompetences INT PRIMARY KEY AUTO_INCREMENT,
    niveau ENUM('Débutant', 'Intermédiaire', 'Avancé', 'Expert'),
    pointsRequis FLOAT,
    descriptionNiveauxCompetences TEXT
);

-- Table des categories d'objets
CREATE TABLE categoriesObjets (
    idCategoriesObjets INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    descriptionCategoriesObjets TEXT
);

-- Table des zones du stade
CREATE TABLE zonesStade (
    idZonesStade INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    descriptionZonesStade TEXT,
    capacite INT
);

-- Table des objets connectés
CREATE TABLE objetsConnectes (
    idObjetsConnectes INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    idCategorie INT,
    descriptionObjetsConnectes TEXT,
    etat ENUM('Actif', 'Inactif', 'Maintenance') DEFAULT 'Actif',
    mode ENUM('Automatique', 'Manuel') DEFAULT 'Automatique',
    connectivite VARCHAR(100),
    niveauBatterie FLOAT,
    derniereInteraction DATETIME,
    puissance FLOAT,
    consommationActuelle FLOAT,
    dureeVieEstimee FLOAT, 
    dateInstallation DATE,
    derniereMaintenance DATE,
    idZone INT,
    FOREIGN KEY (idCategorie) REFERENCES categoriesObjets(idCategoriesObjets),
    FOREIGN KEY (idZone) REFERENCES zonesStade(idZonesStade)
);

-- Table des categories de services
CREATE TABLE categoriesServices (
    idCategoriesServices INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    descriptionCategoriesServices TEXT
);

-- Table des services
CREATE TABLE services (
    idservices INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    idCategorie INT,
    descriptionServices TEXT,
    estActif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (idCategorie) REFERENCES categoriesServices(idCategoriesServices)
);

-- Table des evenements
CREATE TABLE evenements (
    idEvenements INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    dateEvenements DATETIME,
    descriptionEvenements TEXT,
    typeEvents VARCHAR(100),
    equipeDomicile VARCHAR(100),
    equipeExterieur VARCHAR(100),
    prix FLOAT,
    Disponiblilite INT
);

-- Table des connexions des utilisateurs
CREATE TABLE connexionsUtilisateurs (
    idConnexionsUtilisateurs INT PRIMARY KEY AUTO_INCREMENT,
    idUtilisateur INT,
    dateConnexion DATETIME DEFAULT CURRENT_TIMESTAMP,
    pointsGagne FLOAT DEFAULT 0.25,
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs(idUtilisateurs)
);

-- Table des actions des utilisateurs
CREATE TABLE actionsUtilisateurs (
    idActionsUtilisateurs INT PRIMARY KEY AUTO_INCREMENT,
    idUtilisateur INT,
    typeAction ENUM('Consultation', 'Modification', 'Ajout', 'Suppression'),
    entiteCible ENUM('Objet', 'Service', 'Utilisateur', 'Événement'),
    idCible INT, 
    dateAction DATETIME DEFAULT CURRENT_TIMESTAMP,
    pointsGagne FLOAT DEFAULT 0.5,
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs(idUtilisateurs)
);

-- Table de l'historique des objets
CREATE TABLE historiqueObjets (
    idHistoriqueObjets INT PRIMARY KEY AUTO_INCREMENT,
    idObjetsConnectes INT,
    dateModification DATETIME DEFAULT CURRENT_TIMESTAMP,
    ancienEtat VARCHAR(50),
    nouvelEtat VARCHAR(50),
    idUtilisateur INT,
    FOREIGN KEY (idObjetsConnectes) REFERENCES objetsConnectes(idObjetsConnectes),
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs(idUtilisateurs)
);

-- Insertion des niveaux de compétences
INSERT INTO niveauxCompetences (niveau, pointsRequis, descriptionNiveauxCompetences) VALUES
('Débutant', 0, 'Vous avez le niveau d''expérience acquis en classe et/ou dans des scénarios expérimentaux ou en tant que stagiaire sur le lieu de travail. On s''attend à ce que vous ayez besoin d''aide dans l''exercice de cette compétence.'),
('Intermédiaire', 10, 'Vous pouvez accomplir avec succès les tâches qui vous sont demandées dans le cadre de cette compétence. L''aide d''un expert peut s''avérer nécessaire de temps à autre, mais vous pouvez généralement exécuter cette compétence de manière autonome.'),
('Avancé', 25, 'Vous pouvez effectuer les actions associées à cette compétence sans assistance. Vous êtes certainement reconnu au sein de votre organisation immédiate comme "la personne à qui demander" lorsque des questions difficiles se posent au sujet de cette compétence.'),
('Expert', 50, 'Vous êtes reconnu comme un expert dans ce domaine. Vous pouvez fournir des conseils, résoudre des problèmes et répondre à des questions liées à ce domaine d''expertise et au domaine dans lequel la compétence est utilisée.');

-- Insertion des zones du stade
INSERT INTO zonesStade (nom, descriptionZonesStade, capacite) VALUES
('Tribune Nord', 'Tribune principale avec vue sur le terrain', 15000),
('Tribune Sud', 'Tribune face à la tribune Nord', 12000),
('Tribune Est', 'Tribune latérale avec loges VIP', 8000),
('Tribune Ouest', 'Tribune latérale avec espaces familiaux', 8500),
('Terrain', 'Surface de jeu principale', 100),
('Zone technique', 'Zone réservée aux entraîneurs et remplaçants', 50),
('Vestiaires', 'Espace pour les équipes', 100),
('Hall d''entrée', 'Zone d''accueil des spectateurs', 2000),
('Zone de restauration Nord', 'Espace de restauration près de la Tribune Nord', 1000),
('Zone de restauration Sud', 'Espace de restauration près de la Tribune Sud', 1000);

-- Insertion des catégories d'objets
INSERT INTO categoriesObjets (nom, descriptionCategoriesObjets) VALUES
('Éclairage', 'Systèmes d''éclairage intelligents pour le stade'),
('Capteurs', 'Capteurs pour mesurer l''occupation, la température, etc.'),
('Écrans', 'Écrans d''affichage et tableaux de score'),
('Contrôle d''accès', 'Systèmes de contrôle des entrées et sorties'),
('Audio', 'Systèmes de sonorisation'),
('Climatisation', 'Systèmes de chauffage, ventilation et climatisation'),
('Arrosage', 'Systèmes d''arrosage automatique pour le terrain'),
('Surveillance', 'Caméras et systèmes de surveillance'),
('Énergie', 'Gestion de l''énergie et batteries de secours'),
('Connectivité', 'Wi-Fi, Bluetooth et autres systèmes de communication');

-- Insertion des objets connectés
INSERT INTO objetsConnectes (nom, idCategorie, descriptionObjetsConnectes, etat, mode, connectivite, niveauBatterie, derniereInteraction, puissance, consommationActuelle, dureeVieEstimee, dateInstallation, derniereMaintenance, idZone) VALUES
('Projecteur Tribune Est - Secteur A', 1, 'Projecteur LED haute performance', 'Actif', 'Automatique', 'Wi-Fi + Ethernet, signal excellent', 100, '2025-03-25 18:45:00', 1800, 1350, 12500, '2024-01-15', '2025-03-15', 3),
('Projecteur Tribune Est - Secteur B', 1, 'Projecteur LED haute performance', 'Actif', 'Automatique', 'Wi-Fi + Ethernet, signal excellent', 100, '2025-03-25 18:45:00', 1800, 1350, 12000, '2024-01-15', '2025-03-15', 3),
('Projecteur Tribune Nord - Secteur A', 1, 'Projecteur LED haute performance', 'Actif', 'Automatique', 'Wi-Fi + Ethernet, signal bon', 100, '2025-03-25 18:45:00', 1800, 1350, 11800, '2024-01-20', '2025-03-15', 1),
('Projecteur Tribune Sud - Secteur A', 1, 'Projecteur LED haute performance', 'Maintenance', 'Manuel', 'Wi-Fi + Ethernet, signal faible', 100, '2025-03-20 12:30:00', 1800, 0, 10000, '2024-01-20', '2025-03-20', 2),
('Capteur Tribune Nord - Secteur A1', 2, 'Capteur de présence et affluence', 'Actif', 'Automatique', 'Wi-Fi, signal fort', 85, '2025-03-25 15:30:00', 5, 3, 5000, '2024-02-10', '2025-02-10', 1),
('Capteur Tribune Nord - Secteur A2', 2, 'Capteur de présence et affluence', 'Actif', 'Automatique', 'Wi-Fi, signal fort', 82, '2025-03-25 15:30:00', 5, 3, 4800, '2024-02-10', '2025-02-10', 1),
('Capteur Tribune Sud - Secteur A1', 2, 'Capteur de présence et affluence', 'Actif', 'Automatique', 'Wi-Fi, signal moyen', 90, '2025-03-25 15:30:00', 5, 3, 5200, '2024-02-12', '2025-02-12', 2),
('Écran Score Principal', 3, 'Écran géant pour l''affichage du score et des replays', 'Actif', 'Manuel', 'Ethernet, signal excellent', 100, '2025-03-25 19:00:00', 8000, 7500, 25000, '2023-08-15', '2025-01-15', 5),
('Écran Information Tribune Nord', 3, 'Écran d''information pour les spectateurs', 'Actif', 'Automatique', 'Wi-Fi + Ethernet, signal excellent', 100, '2025-03-25 14:00:00', 500, 450, 20000, '2023-09-10', '2025-01-20', 1),
('Écran Information Tribune Sud', 3, 'Écran d''information pour les spectateurs', 'Inactif', 'Automatique', 'Wi-Fi + Ethernet, signal faible', 100, '2025-03-20 10:00:00', 500, 0, 19000, '2023-09-10', '2025-01-20', 2),
('Contrôle Accès Porte A', 4, 'Système de contrôle d''accès biométrique', 'Actif', 'Automatique', 'Ethernet, signal excellent', 100, '2025-03-25 20:30:00', 50, 45, 30000, '2023-11-05', '2025-02-05', 8),
('Contrôle Accès Porte B', 4, 'Système de contrôle d''accès biométrique', 'Actif', 'Automatique', 'Ethernet, signal excellent', 100, '2025-03-25 20:30:00', 50, 45, 30000, '2023-11-05', '2025-02-05', 8),
('Système Son Principal', 5, 'Système audio principal du stade', 'Actif', 'Manuel', 'Ethernet, signal excellent', 100, '2025-03-25 19:15:00', 2000, 1800, 40000, '2023-07-20', '2025-01-10', 5),
('Climatisation Tribune Nord', 6, 'Système de climatisation pour zones VIP', 'Actif', 'Automatique', 'Wi-Fi, signal bon', 100, '2025-03-25 16:00:00', 3000, 2800, 15000, '2024-01-15', '2025-03-10', 1),
('Arrosage Terrain Zone A', 7, 'Système d''arrosage automatique', 'Actif', 'Automatique', 'Wi-Fi, signal excellent', 95, '2025-03-25 06:00:00', 200, 0, 20000, '2023-12-10', '2025-02-28', 5),
('Arrosage Terrain Zone B', 7, 'Système d''arrosage automatique', 'Actif', 'Automatique', 'Wi-Fi, signal excellent', 92, '2025-03-25 06:00:00', 200, 0, 20000, '2023-12-10', '2025-02-28', 5),
('Caméra Surveillance Tribune Nord', 8, 'Caméra haute définition avec reconnaissance faciale', 'Actif', 'Automatique', 'Ethernet, signal excellent', 100, '2025-03-25 21:00:00', 20, 18, 25000, '2023-10-15', '2025-02-15', 1),
('Caméra Surveillance Tribune Sud', 8, 'Caméra haute définition avec reconnaissance faciale', 'Actif', 'Automatique', 'Ethernet, signal excellent', 100, '2025-03-25 21:00:00', 20, 18, 25000, '2023-10-15', '2025-02-15', 2),
('Batterie Secours Éclairage', 9, 'Batterie de secours pour l''éclairage d''urgence', 'Actif', 'Automatique', 'Ethernet, signal bon', 100, '2025-03-15 09:00:00', 5000, 0, 10000, '2024-01-05', '2025-03-05', 5),
('Point d''accès Wi-Fi Tribune Nord', 10, 'Point d''accès Wi-Fi pour les spectateurs', 'Actif', 'Automatique', 'Ethernet, signal excellent', 100, '2025-03-25 14:00:00', 30, 28, 35000, '2023-09-01', '2025-02-01', 1);

-- Insertion des catégories de services
INSERT INTO categoriesServices (nom, descriptionCategoriesServices) VALUES
('Billetterie', 'Services de vente et gestion des billets'),
('Restauration', 'Services de restauration dans le stade'),
('Boutique', 'Boutiques officielles du club'),
('Information', 'Services d''information pour les spectateurs'),
('VIP', 'Services premium pour les zones VIP'),
('Sécurité', 'Services de sécurité et d''urgence'),
('Accessibilité', 'Services pour les personnes à mobilité réduite'),
('Parking', 'Services de stationnement'),
('Divertissement', 'Services de divertissement pendant les pauses'),
('Transport', 'Services de transport vers et depuis le stade');

-- Insertion des services
INSERT INTO services (nom, idCategorie, descriptionServices, estActif) VALUES
('Billetterie en ligne', 1, 'Achat de billets sur le site web ou l''application mobile', TRUE),
('Billetterie physique', 1, 'Guichets de vente de billets au stade', TRUE),
('Fast Food Nord', 2, 'Restauration rapide dans la tribune Nord', TRUE),
('Fast Food Sud', 2, 'Restauration rapide dans la tribune Sud', TRUE),
('Restaurant VIP', 2, 'Restaurant gastronomique pour les zones VIP', TRUE),
('Boutique principale', 3, 'Boutique officielle principale', TRUE),
('Boutique en ligne', 3, 'Boutique officielle sur le site web', TRUE),
('Point d''information Nord', 4, 'Point d''information pour les spectateurs', TRUE),
('Point d''information Sud', 4, 'Point d''information pour les spectateurs', TRUE),
('Loges VIP', 5, 'Loges privées avec services premium', TRUE),
('Service de sécurité', 6, 'Équipe de sécurité présente dans le stade', TRUE),
('Premiers secours', 6, 'Service médical d''urgence', TRUE),
('Accès PMR', 7, 'Accès et places réservées pour les personnes à mobilité réduite', TRUE),
('Parking VIP', 8, 'Parking réservé aux détenteurs de pass VIP', TRUE),
('Parking général', 8, 'Parking pour tous les spectateurs', TRUE),
('Animation mi-temps', 9, 'Animations pendant la mi-temps des matchs', TRUE),
('Navettes gratuites', 10, 'Service de navettes depuis les points de transport en commun', TRUE);

-- Insertion des événements
INSERT INTO evenements (nom, dateEvenements, descriptionEvenements, typeEvents, equipeDomicile, equipeExterieur, prix, Disponiblilite) VALUES
('Match de championnat J1', '2025-04-15 20:00:00', 'Premier match de la saison', 'Match', 'FC Local', 'AS Visiteur', 20.00, 35000),
('Match de championnat J2', '2025-04-29 20:00:00', 'Deuxième journée de championnat', 'Match', 'FC Local', 'SC Rival', 25.00, 32000),
('Match de coupe J1', '2025-05-10 18:30:00', 'Premier tour de coupe', 'Match', 'FC Local', 'US Challenger', 15.00, 40000),
('Match amical international', '2025-07-20 20:00:00', 'Match amical contre une équipe étrangère', 'Match', 'FC Local', 'Inter FC', 30.00, 43000),
('Match de championnat J3', '2025-08-10 20:00:00', 'Troisième journée de championnat', 'Match', 'FC Local', 'Olympique FC', 25.00, 35000);

-- Insertion des utilisateurs
INSERT INTO utilisateurs (pseudo, email, mdp, nom, prenom, dateNaissance, age, sexe, typeMembre, niveau, points, photo, dateInscription, derniereConnexion, estActif, estVerifie) VALUES
('admin1', 'admin@stadeconnecte.fr', 'mdp', 'Admin', 'Principal', '1985-05-15', 40, 'Homme', 'Administratif', 'Expert', 100.0, 'admin1.jpg', '2023-10-01 10:00:00', '2025-03-25 09:15:00', TRUE, TRUE),
('technicien1', 'tech@stadeconnecte.fr', 'mdp', 'Dupont', 'Jean', '1990-02-20', 35, 'Homme', 'Personnel technique', 'Expert', 75.5, 'tech1.jpg', '2023-10-05 11:30:00', '2025-03-24 14:20:00', TRUE, TRUE),
('securite1', 'securite@stadeconnecte.fr', 'mdp', 'Martin', 'Sophie', '1988-11-12', 37, 'Femme', 'Sécurité', 'Avancé', 45.0, 'securite1.jpg', '2023-10-10 09:45:00', '2025-03-25 08:30:00', TRUE, TRUE),
('entraineur1', 'coach@stadeconnecte.fr', 'mdp', 'Garcia', 'Luis', '1975-06-30', 50, 'Homme', 'Entraîneur', 'Avancé', 40.0, 'coach1.jpg', '2023-11-05 15:20:00', '2025-03-24 18:45:00', TRUE, TRUE),
('athlete1', 'joueur@stadeconnecte.fr', 'mdp', 'Dubois', 'Thomas', '1998-03-25', 27, 'Homme', 'Athlète', 'Intermédiaire', 22.5, 'athlete1.jpg', '2023-11-10 12:15:00', '2025-03-25 17:30:00', TRUE, TRUE),
('spectateur1', 'fan1@mail.com', 'mdp', 'Petit', 'Marie', '1992-09-18', 33, 'Femme', 'Spectateur', 'Intermédiaire', 15.0, 'spec1.jpg', '2023-12-01 10:30:00', '2025-03-22 20:15:00', TRUE, TRUE),
('spectateur2', 'fan2@mail.com', 'mdp', 'Moreau', 'Paul', '1995-07-22', 30, 'Homme', 'Spectateur', 'Débutant', 6.5, 'spec2.jpg', '2024-01-15 14:20:00', '2025-03-21 19:45:00', TRUE, TRUE),
('spectateur3', 'fan3@mail.com', 'mdp', 'Leroy', 'Julie', '1989-12-05', 36, 'Femme', 'Spectateur', 'Débutant', 3.0, 'spec3.jpg', '2024-02-10 16:45:00', '2025-03-15 15:30:00', TRUE, TRUE),
('administratif1', 'bureau@stadeconnecte.fr', 'mdp', 'Fournier', 'Claire', '1985-04-12', 40, 'Femme', 'Administratif', 'Expert', 82.0, 'admin2.jpg', '2023-10-02 11:15:00', '2025-03-25 10:30:00', TRUE, TRUE),
('invite1', 'invite@mail.com', 'mdp', 'Blanc', 'Michel', '1980-08-30', 45, 'Homme', 'Spectateur', 'Débutant', 0, 'invite1.jpg', '2025-03-01 09:30:00', '2025-03-01 09:30:00', TRUE, FALSE);

-- Insertion des connexions utilisateurs (30 derniers jours)
INSERT INTO connexionsUtilisateurs (idUtilisateur, dateConnexion, pointsGagne) VALUES
(1, '2025-03-01 09:15:00', 0.25),
(1, '2025-03-05 10:20:00', 0.25),
(1, '2025-03-10 08:45:00', 0.25),
(1, '2025-03-15 11:30:00', 0.25),
(1, '2025-03-20 09:20:00', 0.25),
(1, '2025-03-25 09:15:00', 0.25),
(2, '2025-03-02 14:20:00', 0.25),
(2, '2025-03-05 13:15:00', 0.25),
(2, '2025-03-10 14:30:00', 0.25),
(2, '2025-03-15 15:20:00', 0.25),
(2, '2025-03-20 14:15:00', 0.25),
(2, '2025-03-24 14:20:00', 0.25),
(3, '2025-03-01 08:30:00', 0.25),
(3, '2025-03-05 08:15:00', 0.25),
(3, '2025-03-10 08:30:00', 0.25),
(3, '2025-03-15 08:15:00', 0.25),
(3, '2025-03-20 08:30:00', 0.25),
(3, '2025-03-25 08:30:00', 0.25),
(4, '2025-03-02 18:45:00', 0.25),
(4, '2025-03-08 17:30:00', 0.25),
(4, '2025-03-15 18:15:00', 0.25),
(4, '2025-03-20 19:00:00', 0.25),
(4, '2025-03-24 18:45:00', 0.25),
(5, '2025-03-05 17:30:00', 0.25),
(5, '2025-03-10 16:45:00', 0.25),
(5, '2025-03-15 17:15:00', 0.25),
(5, '2025-03-20 16:30:00', 0.25),
(5, '2025-03-25 17:30:00', 0.25),
(6, '2025-03-01 20:15:00', 0.25),
(6, '2025-03-08 19:45:00', 0.25),
(6, '2025-03-15 20:30:00', 0.25),
(6, '2025-03-22 20:15:00', 0.25),
(7, '2025-03-03 19:45:00', 0.25),
(7, '2025-03-10 19:30:00', 0.25),
(7, '2025-03-17 19:15:00', 0.25),
(7, '2025-03-21 19:45:00', 0.25),
(8, '2025-03-01 15:30:00', 0.25),
(8, '2025-03-08 16:15:00', 0.25),
(8, '2025-03-15 15:30:00', 0.25),
(9, '2025-03-01 10:30:00', 0.25),
(9, '2025-03-05 09:45:00', 0.25),
(9, '2025-03-10 11:15:00', 0.25),
(9, '2025-03-15 10:30:00', 0.25),
(9, '2025-03-20 09:45:00', 0.25),
(9, '2025-03-25 10:30:00', 0.25);

-- Insertion des actions utilisateurs
INSERT INTO actionsUtilisateurs (idUtilisateur, typeAction, entiteCible, idCible, dateAction, pointsGagne) VALUES
-- Admin consulte différents objets
(1, 'Consultation', 'Objet', 1, '2025-03-01 09:30:00', 0.5),
(1, 'Consultation', 'Objet', 5, '2025-03-01 09:45:00', 0.5),
(1, 'Consultation', 'Objet', 10, '2025-03-05 10:30:00', 0.5),
(1, 'Consultation', 'Objet', 15, '2025-03-10 09:00:00', 0.5),
-- Admin modifie des objets
(1, 'Modification', 'Objet', 4, '2025-03-20 09:30:00', 0.5),
(1, 'Modification', 'Objet', 10, '2025-03-25 09:30:00', 0.5),
-- Admin consulte et modifie des utilisateurs
(1, 'Consultation', 'Utilisateur', 5, '2025-03-15 11:45:00', 0.5),
(1, 'Modification', 'Utilisateur', 7, '2025-03-20 09:40:00', 0.5),
-- Technicien consulte et modifie des objets
(2, 'Consultation', 'Objet', 1, '2025-03-02 14:30:00', 0.5),
(2, 'Consultation', 'Objet', 2, '2025-03-02 14:45:00', 0.5),
(2, 'Consultation', 'Objet', 3, '2025-03-05 13:30:00', 0.5),
(2, 'Modification', 'Objet', 1, '2025-03-10 14:45:00', 0.5),
(2, 'Modification', 'Objet', 3, '2025-03-15 15:30:00', 0.5),
(2, 'Ajout', 'Objet', 20, '2025-03-20 14:30:00', 0.5),
-- Sécurité consulte les objets de surveillance
(3, 'Consultation', 'Objet', 18, '2025-03-01 08:45:00', 0.5),
(3, 'Consultation', 'Objet', 19, '2025-03-05 08:30:00', 0.5),
(3, 'Consultation', 'Objet', 11, '2025-03-10 08:45:00', 0.5),
(3, 'Consultation', 'Objet', 12, '2025-03-15 08:30:00', 0.5),
(3, 'Modification', 'Objet', 18, '2025-03-20 08:45:00', 0.5),
-- Entraîneur consulte les services
(4, 'Consultation', 'Service', 5, '2025-03-02 19:00:00', 0.5),
(4, 'Consultation', 'Service', 12, '2025-03-08 17:45:00', 0.5),
(4, 'Consultation', 'Service', 13, '2025-03-15 18:30:00', 0.5),
-- Athlète consulte les objets et services
(5, 'Consultation', 'Objet', 13, '2025-03-05 17:45:00', 0.5);

-- Insertion de l'historique des objets
INSERT INTO historiqueObjets (idObjetsConnectes, dateModification, ancienEtat, nouvelEtat, idUtilisateur) VALUES 
(1, DEFAULT, 'Inactif', 'Actif', 1);

-- Affichage de la base de données
SELECT * FROM utilisateurs;
SELECT * FROM services;
SELECT * FROM actionsUtilisateurs;
SELECT * FROM historiqueObjets;
SELECT * FROM connexionsUtilisateurs;
SELECT * FROM evenements;
SELECT * FROM categoriesServices;
SELECT * FROM objetsConnectes;
SELECT * FROM categoriesObjets;
SELECT * FROM zonesStade;
SELECT * FROM niveauxCompetences;
