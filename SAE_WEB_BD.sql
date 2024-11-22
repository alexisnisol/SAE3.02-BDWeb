-- Ajout des DROP
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
  niveau INT CHECK(niveau > 0 AND niveau < 6),
  duree INT CHECK(duree > 0 AND duree < 3),
  heure TIME CHECK(HOUR(heure) > 0 AND HOUR(heure) < 25),
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
  poids FLOAT NULL CHECK(poids > 9 AND poids < 51),
  cotisation DECIMAL(10, 2) NULL,
  date_inscription date NOT NULL,
  niveau INT NULL CHECK(niveau > 0 AND niveau < 6)
);

-- Création de la table PONEY
CREATE TABLE PONEY (
  id INT PRIMARY KEY,
  nom VARCHAR(42),
  age INT CHECK(age > 0 AND age < 26),
  poids_max FLOAT CHECK(poids_max > 9 AND poids_max < 51)
);

-- Création de la table COURS_REALISE avec clés étrangères
CREATE TABLE COURS_REALISE (
  id_cours INT NOT NULL,
  id_moniteur INT NOT NULL,
  dateR DATETIME NOT NULL,
  PRIMARY KEY (id_cours, dateR),
  FOREIGN KEY (id_cours) REFERENCES COURS_PROGRAMME (id_cp),
  FOREIGN KEY (id_moniteur) REFERENCES PERSONNE (id_p)
);

-- Création de la table RESERVER avec clés étrangères
CREATE TABLE RESERVER (
  id_client INT NOT NULL,
  id_poney INT NOT NULL,
  id_cours INT NOT NULL,
  dateR DATETIME NOT NULL,
  PRIMARY KEY (id_client, id_poney, id_cours, dateR),
  FOREIGN KEY (id_client) REFERENCES PERSONNE (id_p),
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

--   -- Récupérer le poids de la personne réservée
--   SELECT poids INTO poids_personne
--   FROM PERSONNE
--   WHERE id_p = NEW.id_client;

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

  -- Verifie la date du cours_realise est bien compris dans la periode du cours programme 

DELIMITER |
CREATE OR REPLACE TRIGGER VerifierDatePeriode
BEFORE INSERT ON COURS_REALISE
FOR EACH ROW
BEGIN
    DECLARE debut DATE;
    DECLARE fin DATE;
    DECLARE date_rea DATE;
    DECLARE heure_deb_prog TIME;
    DECLARE heure_rea TIME;

    -- Récupérer les dates et l'heure du cours dans COURS_PROGRAMME
    SELECT Ddd, Ddf, heure INTO debut, fin, heure_deb_prog
    FROM COURS_PROGRAMME
    WHERE id_cp = NEW.id_cours;

    -- Extraire la date et l'heure de NEW.dateR
    SET date_rea = DATE(NEW.dateR);
    SET heure_rea = TIME(NEW.dateR);

    -- Vérifier si la date et l'heure du cours réalisé sont dans la période définie dans COURS_PROGRAMME
    IF date_rea < debut OR date_rea > fin OR heure_rea != heure_deb_prog THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Erreur : la date du cours est en dehors des dates programmées";
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

DELIMITER |
create or replace trigger VerifierNiveauPersonne before insert on RESERVER FOR EACH ROW
BEGIN
  declare niveau_personne int;
  declare niveau_cours int;

  -- Récupérer le niveau de la personne réservée
  SELECT niveau INTO niveau_personne
  FROM PERSONNE
  WHERE id_p = NEW.id_client;

  -- Récupérer le niveau du cours réservé
  SELECT niveau INTO niveau_cours
  FROM COURS_PROGRAMME
  WHERE id_cp = NEW.id_cours;

  -- Vérifier si le niveau de la personne est compatible avec le niveau du cours
  IF niveau_personne < niveau_cours THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erreur : le niveau de la personne est inférieur au niveau du cours.';
  END IF;
END |
DELIMITER ;

------------------------------------------------------------------------------------------------------------------------

  -- Vérifier que l'id du poney n'est pas déjà entrain de réaliser un autre cours à la même heure lors d'un réservation

DELIMITER |
CREATE OR REPLACE TRIGGER VerifierPoneyOccupe
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  DECLARE poney_occupe INT; -- Utiliser INT pour correspondre au résultat d'EXISTS

  -- Vérifier si le poney est déjà assigné à un cours à la même heure
  SELECT EXISTS (
    SELECT 1
    FROM RESERVER
    WHERE id_poney = NEW.id_poney
      AND dateR = NEW.dateR
  )
  INTO poney_occupe;

  IF poney_occupe = 1 THEN -- Vérifier si le poney est occupé
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erreur : Le poney est déjà réservé pour un autre cours à cette heure.';
  END IF;
END |
DELIMITER ;

------------------------------------------------------------------------------------------------------------------------

DELIMITER |
CREATE OR REPLACE TRIGGER VerifierMoniteurOccupe
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  DECLARE moniteur_occupe BOOLEAN;

  -- Vérifier si le moniteur est déjà assigné à un autre cours à la même heure
  SELECT EXISTS (
    SELECT 1
    FROM COURS_REALISE
    WHERE id_moniteur = (SELECT id_moniteur FROM COURS_REALISE WHERE id_cp = NEW.id_cours)
      AND dateR = NEW.dateR
  )
  INTO moniteur_occupe;

  IF moniteur_occupe THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erreur : Le moniteur est déjà réservé pour un autre cours à cette heure.';
  END IF;
END |
DELIMITER ;

-------------------------------------------------------------------------------------------------------------------------

-- Trigger : nb_personnes_max pas dépassé pour la reservation d'un cours
DELIMITER |
CREATE OR REPLACE TRIGGER VerifierEstMoniteur
BEFORE INSERT ON COURS_REALISE
FOR EACH ROW
BEGIN

  DECLARE est_moniteur BOOLEAN default false;

  -- Vérifier si la personne est un moniteur
  select (salaire IS NOT NULL) into est_moniteur from PERSONNE where id_p = NEW.id_moniteur;

  if not est_moniteur then
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = "Erreur : la personne n'est pas un moniteur.";
  END IF;
END |
DELIMITER ;
