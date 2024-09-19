--CREATION DE LA BASE DE DONNEES--

-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS RESERVER;
DROP TABLE IF EXISTS COURS_REALISE;
DROP TABLE IF EXISTS PONEY;
DROP TABLE IF EXISTS PERSONNE;
DROP TABLE IF EXISTS COURS_PROGRAMME;

-- Création de la table COURS_PROGRAMME
CREATE TABLE COURS_PROGRAMME (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom_cours VARCHAR(42),
  niveau INT,
  duree INT check (duree > 0 and duree < 3),
  heure TIME,
  jour varchar(16) check (jour = 'Lundi' or jour = 'Mardi' or jour = 'Mercredi' or jour = 'Jeudi' or jour = 'Vendredi' or jour = 'Samedi' or jour = 'Dimanche'),
  Ddd DATE,
  Ddf DATE,
  nb_personnes_max INT check (nb_personnes_max > 0 and nb_personnes_max < 11)
);

-- Création de la table PERSONNE
CREATE TABLE PERSONNE (
  id INT PRIMARY KEY,
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
  niveau INT NULL
);

-- Création de la table PONEY
CREATE TABLE PONEY (
  id INT PRIMARY KEY,
  nom VARCHAR(42),
  age INT,
  poids_max FLOAT,
  heures_travail INT
);

-- Création de la table COURS_REALISE avec clés étrangères
CREATE TABLE COURS_REALISE (
  id_cours INT NOT NULL,
  id_personne int NOT NULL,
  date DATE NOT NULL,
  PRIMARY KEY (id_cours, date),
  FOREIGN KEY (id_cours) REFERENCES COURS_PROGRAMME (id),
  FOREIGN KEY (id_personne) REFERENCES PERSONNE (id)
);

-- Création de la table RESERVER avec clés étrangères
CREATE TABLE RESERVER (
  id_personne int NOT NULL,
  id_poney int NOT NULL,
  id_cours INT NOT NULL,
  date DATE NOT NULL,
  PRIMARY KEY (id_personne, id_poney, id_cours, date),
  FOREIGN KEY (id_personne) REFERENCES PERSONNE (id),
  FOREIGN KEY (id_poney) REFERENCES PONEY (id),
  FOREIGN KEY (id_cours, date) REFERENCES COURS_REALISE (id_cours, date)
);



insert into COURS_PROGRAMME values (1, 'C1', 1, 1, '10:00:00', 'Lundi', '2020-01-01', '2020-12-31', 10);
insert into COURS_PROGRAMME values (2, 'C2', 1, 1, '11:00:00', 'Mardi', '2020-01-01', '2020-12-31', 10);
insert into COURS_PROGRAMME values (3, 'C3', 1, 2, '12:00:00', 'Mercredi', '2020-01-01', '2020-12-31', 10);