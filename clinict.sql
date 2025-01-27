CREATE DATABASE db;
USE db;

CREATE TABLE administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    prenom VARCHAR(100),
    nom VARCHAR(100),
    email VARCHAR(100),
    telephone VARCHAR(15),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO administrateurs (nom_utilisateur, mot_de_passe, prenom, nom, email, telephone)
VALUES ('admin', MD5('admin123'), 'Admin', 'Test', 'admin@example.com', '0612345678');
CREATE TABLE dentistes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    prenom VARCHAR(100),
    nom VARCHAR(100),
    email VARCHAR(100),
    telephone VARCHAR(15),
    specialisation VARCHAR(255),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO dentistes (nom_utilisateur, mot_de_passe, prenom, nom, email, telephone, specialisation)
VALUES ('dentiste_user', MD5('dentiste123'), 'Jean', 'Dupont', 'dentiste@example.com', '0612345679', 'Orthodontie');
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100),
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    telephone VARCHAR(15),
    date_naissance DATE,
    adresse TEXT,
    sexe ENUM('masculin', 'féminin', 'autre'),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    dentiste_id INT NOT NULL,
    date_rendez_vous DATETIME NOT NULL,
    statut ENUM('en attente', 'confirmé', 'terminé', 'annulé') DEFAULT 'en attente',
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (dentiste_id) REFERENCES dentistes(id) ON DELETE CASCADE
);


CREATE TABLE stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_article VARCHAR(255) NOT NULL,
    quantite INT NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    fournisseur VARCHAR(255),
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE protheses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_prothese VARCHAR(255) NOT NULL,
    patient_id INT NOT NULL,
    dentiste_id INT NOT NULL,
    date_production DATE,
    statut ENUM('en cours', 'terminée', 'livrée') DEFAULT 'en cours',
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (dentiste_id) REFERENCES dentistes(id) ON DELETE CASCADE
);

CREATE TABLE calendrier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    statut ENUM('disponible', 'complet') DEFAULT 'disponible',
    max_reservations INT DEFAULT 10,
    reservations INT DEFAULT 0
);

CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP,
    moyen_paiement ENUM('espèces', 'carte', 'virement', 'autre') DEFAULT 'autre',
    commentaire TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);
