--Ajout des DROP
DROP TABLE IF EXISTS RESERVER;
DROP TABLE IF EXISTS COURS_REALISE;
DROP TABLE IF EXISTS PONEY;
DROP TABLE IF EXISTS PERSONNE;
DROP TABLE IF EXISTS COURS_PROGRAMME;

-- CREATION DE LA BASE DE DONNEES

-- Création de la table COURS_PROGRAMME
CREATE TABLE COURS_PROGRAMME (
  id_cp INT PRIMARY KEY AUTO_INCREMENT,
  nom_cours VARCHAR(42),
  niveau INT,
  duree INT CHECK (duree > 0 AND duree < 3),
  heure TIME,
  jour VARCHAR(16) CHECK (jour IN ('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')),
  Ddd date,
  Ddf date,
  nb_personnes_max INT CHECK (nb_personnes_max > 0 AND nb_personnes_max < 11)
);

-- Création de la table PERSONNE
CREATE TABLE PERSONNE (
  id_p INT PRIMARY KEY,
  nom VARCHAR(42),
  prenom VARCHAR(42),
  adresse VARCHAR(100),
  telephone VARCHAR(15),
  email VARCHAR(100),
  experience TEXT NULL,
  salaire DECIMAL(10, 2) NULL,
  poids FLOAT NULL,
  cotisation DECIMAL(10, 2) NULL,
  date_inscription date NULL,
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
  id_personne INT NOT NULL,
  dateR date NOT NULL,
  PRIMARY KEY (id_cours, dateR),
  FOREIGN KEY (id_cours) REFERENCES COURS_PROGRAMME (id_cp),
  FOREIGN KEY (id_personne) REFERENCES PERSONNE (id_p)
);

-- Création de la table RESERVER avec clés étrangères
CREATE TABLE RESERVER (
  id_personne INT NOT NULL,
  id_poney INT NOT NULL,
  id_cours INT NOT NULL,
  dateR date NOT NULL,
  PRIMARY KEY (id_personne, id_poney, id_cours, dateR),
  FOREIGN KEY (id_personne) REFERENCES PERSONNE (id_p),
  FOREIGN KEY (id_poney) REFERENCES PONEY (id),
  FOREIGN KEY (id_cours) REFERENCES COURS_REALISE (id_cours)
);

----------------------------------------------------

-- Trigger : Poids max du client ne doit pas dépasser le poids max supportable par le poney
DELIMITER |
CREATE OR REPLACE TRIGGER VerifierPoidsPoney
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  DECLARE poids_personne FLOAT;

  -- Récupérer le poids de la personne réservée
  SELECT poids INTO poids_personne
  FROM PERSONNE
  WHERE id_p = NEW.id_personne;

  -- Vérifier si le poids de la personne dépasse le poids maximum du poney
  IF poids_personne > (SELECT poids_max FROM PONEY WHERE id = NEW.id_poney) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erreur : la personne ne peut pas monter sur ce poney, son poids dépasse le poids maximum.';
  END IF;
END |
DELIMITER ;

--------------------------------------------------


-- Trigger : Vérifier que les poneys ont au moins 1 heure de repos après 2 heures de cours
DELIMITER |
CREATE OR REPLACE TRIGGER VerifierReposPoney
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  DECLARE cours_consecutifs INT;
  DECLARE dernier_cours dateTIME;

  -- Vérifier combien de cours consécutifs le poney a donnés avant la nouvelle réservation
  SELECT COUNT(*) INTO cours_consecutifs
  FROM RESERVER
  WHERE id_poney = NEW.id_poney
  AND dateR = NEW.dateR
  AND id_cours IN (SELECT id_cours FROM COURS_REALISE WHERE id_personne = NEW.id_personne);

  -- Vérifier la dateR et l'heure du dernier cours
  SELECT MAX(CONCAT(dateR, ' ', COURS_PROGRAMME.heure)) INTO dernier_cours
  FROM RESERVER
  JOIN COURS_REALISE ON RESERVER.id_cours = COURS_REALISE.id_cours
  WHERE id_poney = NEW.id_poney
  AND dateR < NEW.dateR;

  -- Si le poney a déjà donné 2 heures de cours consécutives sans repos
  IF cours_consecutifs >= 2 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erreur : le poney doit avoir au moins 1 heure de repos après 2 heures de cours.';
  END IF;

  -- Si le dernier cours était à moins d'une heure de la nouvelle réservation
  IF dernier_cours IS NOT NULL AND TIMESTAMPDIFF(HOUR, dernier_cours, NEW.dateR) < 1 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erreur : le poney doit avoir au moins 1 heure de repos après le dernier cours.';
  END IF;
END |
DELIMITER ;

-----------------------------------------------------------------

-- Insertion dans COURS_PROGRAMME
INSERT INTO COURS_PROGRAMME (id_cp, nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES 
(1, 'C1', 1, 1, '10:00:00', 'Lundi', '2020-01-01', '2020-12-31', 10),
(2, 'C2', 1, 1, '11:00:00', 'Mardi', '2020-01-01', '2020-12-31', 10),
(3, 'C3', 1, 2, '12:00:00', 'Mercredi', '2020-01-01', '2020-12-31', 10);


-- Insertion dans PERSONNE
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES 
(1, 'Martin', 'Paul', '123 Rue Principale, Paris', '0102030405', 'paul.martin@example.com', '5 ans experience', NULL, 70.0, NULL, '2023-01-15', 2),
(2, 'Dupont', 'Anne', '45 Avenue des Champs, Lyon', '0607080910', 'anne.dupont@example.com', '3 ans experience', NULL, 85.0, NULL, '2023-02-10', 3);

-- Insertion dans PONEY
INSERT INTO PONEY (id, nom, age, poids_max, heures_travail)
VALUES 
(1, 'Tornado', 7, 75.0, 20),  -- Poney avec poids max de 75 kg
(2, 'Fury', 5, 90.0, 25);      -- Poney avec poids max de 90 kg

-- Insertion dans COURS_REALISE
INSERT INTO COURS_REALISE (id_cours, id_personne, dateR)
VALUES 
(1, 1, '2023-10-01'),  -- Cours pour Paul
(2, 2, '2023-10-02');  -- Cours pour Anne

-- Cette insertion devrait réussir (Paul pèse 70 kg, poney max 75 kg)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (1, 1, 1, '2023-10-01');

-- Cette insertion devrait échouer (Anne pèse 85 kg, poney max 75 kg)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (2, 1, 2, '2023-10-02');