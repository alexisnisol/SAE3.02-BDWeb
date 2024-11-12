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
  dateR DATETIME NOT NULL,
  PRIMARY KEY (id_cours, dateR),
  FOREIGN KEY (id_cours) REFERENCES COURS_PROGRAMME (id_cp),
  FOREIGN KEY (id_personne) REFERENCES PERSONNE (id_p)
);

-- Création de la table RESERVER avec clés étrangères
CREATE TABLE RESERVER (
  id_personne INT NOT NULL,
  id_poney INT NOT NULL,
  id_cours INT NOT NULL,
  dateR DATETIME NOT NULL,
  PRIMARY KEY (id_personne, id_poney, id_cours, dateR),
  FOREIGN KEY (id_personne) REFERENCES PERSONNE (id_p),
  FOREIGN KEY (id_poney) REFERENCES PONEY (id),
  FOREIGN KEY (id_cours, dateR) REFERENCES COURS_REALISE (id_cours, dateR)
);

----------------------------------------------------

-- Trigger : Poids max du client ne doit pas dépasser le poids max supportable par le poney
DELIMITER |
CREATE OR REPLACE TRIGGER VerifierPoidsPoney
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  DECLARE poids_personne FLOAT;
  declare error_msg varchar(255);

  -- Récupérer le poids de la personne réservée
  SELECT poids INTO poids_personne
  FROM PERSONNE
  WHERE id_p = NEW.id_personne;

  -- Vérifier si le poids de la personne dépasse le poids maximum du poney
  IF poids_personne > (SELECT poids_max FROM PONEY WHERE id = NEW.id_poney) THEN
    set error_msg = concat('Erreur : la personne ne peut pas monter sur ce poney, son poids dépasse le poids maximum. Poids de la personne : ', poids_personne, ' > ', (SELECT poids_max FROM PONEY WHERE id = NEW.id_poney));
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = error_msg;
  END IF;
END |
DELIMITER ;

--------------------------------------------------


-- Trigger : Vérifier que les poneys ont au moins 1 heure de repos après 2 heures de cours

DELIMITER |
create or replace trigger VerifierReposPoney before insert on RESERVER FOR EACH ROW
BEGIN
  declare cours_consecutifs int;
  DECLARE total_heures INT DEFAULT 5;
  declare heure_depuis_dernier_cours int;
  
  -- Calculer le total des heures travaillées par le poney dans les 2 heures précédentes à la nouvelle réservation
  SELECT IFNULL(SUM(C.duree), 0)
  INTO total_heures
  FROM RESERVER R
  JOIN COURS_PROGRAMME C ON R.id_cours = C.id_cp
  WHERE R.id_poney = NEW.id_poney
    AND TIMESTAMPDIFF(HOUR, R.dateR, NEW.dateR) <= 2
    AND R.dateR < NEW.dateR;

  IF total_heures != 0 THEN
      SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Le poney doit avoir une heure de repos après deux heures de travail consécutif.';
  END IF;
END |
DELIMITER ;


-----------------------------------------------------------------

-- Trigger : nb_personnes_max pas dépassé pour la reservation d'un cours
DELIMITER |
CREATE OR REPLACE TRIGGER VerifierNbPersonnesMax
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  DECLARE nb_reservations INT;

  -- Calculer le nombre de réservations actuelles pour le cours
  SELECT COUNT(*)
  INTO nb_reservations
  FROM RESERVER
  WHERE id_cours = NEW.id_cours
    AND dateR = NEW.dateR;

  -- Vérifier si le nombre de réservations dépasse le maximum autorisé
  IF nb_reservations >= (SELECT nb_personnes_max FROM COURS_PROGRAMME WHERE id_cp = NEW.id_cours) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erreur : le nombre maximal de personnes pour ce cours a été atteint.';
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
(2, 'Dupont', 'Anne', '45 Avenue des Champs, Lyon', '0607080910', 'anne.dupont@example.com', '3 ans experience', NULL, 85.0, NULL, '2023-02-10', 3),
(3, 'Personne1', 'Test', 'Adresse1', '0101010101', 'personne1@example.com', NULL, NULL, 70.0, NULL, '2023-01-01', 1),
(4, 'Personne2', 'Test', 'Adresse2', '0202020202', 'personne2@example.com', NULL, NULL, 70.0, NULL, '2023-01-02', 1),
(5, 'Personne3', 'Test', 'Adresse3', '0303030303', 'personne3@example.com', NULL, NULL, 70.0, NULL, '2023-01-03', 1),
(6, 'Personne4', 'Test', 'Adresse4', '0404040404', 'personne4@example.com', NULL, NULL, 70.0, NULL, '2023-01-04', 1),
(7, 'Personne5', 'Test', 'Adresse5', '0505050505', 'personne5@example.com', NULL, NULL, 70.0, NULL, '2023-01-05', 1),
(8, 'Personne6', 'Test', 'Adresse6', '0606060606', 'personne6@example.com', NULL, NULL, 70.0, NULL, '2023-01-06', 1),
(9, 'Personne7', 'Test', 'Adresse7', '0707070707', 'personne7@example.com', NULL, NULL, 70.0, NULL, '2023-01-07', 1),
(10, 'Personne8', 'Test', 'Adresse8', '0808080808', 'personne8@example.com', NULL, NULL, 70.0, NULL, '2023-01-08', 1),
(11, 'Personne9', 'Test', 'Adresse9', '0909090909', 'personne9@example.com', NULL, NULL, 70.0, NULL, '2023-01-09', 1),
(12, 'Personne10', 'Test', 'Adresse10', '1010101010', 'personne10@example.com', NULL, NULL, 70.0, NULL, '2023-01-10', 1);

-- Insertion dans PONEY
INSERT INTO PONEY (id, nom, age, poids_max, heures_travail)
VALUES 
(1, 'Tornado', 7, 75.0, 20),  -- Poney avec poids max de 75 kg
(2, 'Fury', 5, 90.0, 25);      -- Poney avec poids max de 90 kg

-- Insertion dans COURS_REALISE
INSERT INTO COURS_REALISE (id_cours, id_personne, dateR)
VALUES 
(1, 1, '2023-10-01 10:00:00'),  -- Cours pour Paul
(1, 1, '2023-10-01 12:00:00'),  -- Cours pour Paul
(1, 1, '2023-10-01 13:00:00'),  -- Cours pour Paul
(1, 1, '2023-10-02 13:00:00'),  -- Cours pour Paul
(2, 2, '2023-10-02 11:00:00');  -- Cours pour Anne

-- Insertion dans RESERVER
-- Réservations valides
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES 
(1, 1, 1, '2023-10-01 10:00:00'),  -- Réservation réussie pour Paul
(2, 2, 2, '2023-10-02 11:00:00');  -- Réservation réussie pour Anne

-- Ajout de réservations pour le cours 1
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES 
(3, 1, 1, '2023-10-01 10:00:00'),
(4, 1, 1, '2023-10-01 10:00:00'),
(5, 1, 1, '2023-10-01 10:00:00'),
(6, 1, 1, '2023-10-01 10:00:00'),
(7, 1, 1, '2023-10-01 10:00:00'),
(8, 1, 1, '2023-10-01 10:00:00'),
(9, 1, 1, '2023-10-01 10:00:00'),
(10, 1, 1, '2023-10-01 10:00:00'),
(11, 1, 1, '2023-10-01 10:00:00');  -- Dernière réservation réussie

-- 12ème réservation échouée (dépassement du nombre maximal de personnes)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (12, 1, 1, '2023-10-01 10:00:00');  -- Cette insertion doit déclencher le trigger et échouer