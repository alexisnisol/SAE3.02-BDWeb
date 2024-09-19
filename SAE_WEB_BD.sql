--CREATION DE LA BASE DE DONNEES--
DROP TABLE IF EXISTS RESERVER;
DROP TABLE IF EXISTS COURS_REALISE;
DROP TABLE IF EXISTS PONEY;
DROP TABLE IF EXISTS PERSONNE;
DROP TABLE IF EXISTS COURS_PROGRAMME;

-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS RESERVER;
DROP TABLE IF EXISTS COURS_REALISE;
DROP TABLE IF EXISTS PONEY;
DROP TABLE IF EXISTS PERSONNE;
DROP TABLE IF EXISTS COURS_PROGRAMME;

-- Création de la table COURS_PROGRAMME
CREATE TABLE COURS_PROGRAMME (
  id_cours INT NOT NULL AUTO_INCREMENT,
  nom_cours VARCHAR(42),
  niveau INT,
  duree INT,
  heure TIME,
  jour ENUM('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'),
  Ddd DATE,
  Ddf DATE,
  nb_personnes_max INT,
  PRIMARY KEY (id_cours)
);

-- Création de la table PERSONNE
CREATE TABLE PERSONNE (
  id_personne VARCHAR(42) NOT NULL,
  nom VARCHAR(42),
  prenom VARCHAR(42),
  adresse VARCHAR(100),
  telephone VARCHAR(15),
  email VARCHAR(100),
  experience TEXT NULL,
  salaire DECIMAL(10, 2) NULL,
  poids FLOAT NULL,
  cotisation DECIMAL(10, 2) NULL,
  date_inscription DATE NULL,
  niveau INT NULL,
  PRIMARY KEY (id_personne)
);

-- Création de la table PONEY
CREATE TABLE PONEY (
  id_poney VARCHAR(42) NOT NULL,
  nom VARCHAR(42),
  age INT,
  poids_max FLOAT,
  heures_travail INT,
  PRIMARY KEY (id_poney)
);

-- Création de la table COURS_REALISE avec clés étrangères
CREATE TABLE COURS_REALISE (
  id_cours INT NOT NULL,
  date DATE NOT NULL,
  id_personne VARCHAR(42) NOT NULL,
  PRIMARY KEY (id_cours, date),
  FOREIGN KEY (id_cours) REFERENCES COURS_PROGRAMME (id_cours),
  FOREIGN KEY (id_personne) REFERENCES PERSONNE (id_personne)
);

-- Création de la table RESERVER avec clés étrangères
CREATE TABLE RESERVER (
  id_personne_composee VARCHAR(42) NOT NULL,
  id_poney_composante VARCHAR(42) NOT NULL,
  id_cours INT NOT NULL,
  date DATE NOT NULL,
  PRIMARY KEY (id_personne_composee, id_poney_composante, id_cours, date),
  FOREIGN KEY (id_personne_composee) REFERENCES PERSONNE (id_personne),
  FOREIGN KEY (id_poney_composante) REFERENCES PONEY (id_poney),
  FOREIGN KEY (id_cours, date) REFERENCES COURS_REALISE (id_cours, date)
);