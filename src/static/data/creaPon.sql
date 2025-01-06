-- Activation des foreign keys
PRAGMA foreign_keys = ON;

-- Suppression des tables existantes
DROP TABLE IF EXISTS RESERVER;
DROP TABLE IF EXISTS COURS_REALISE;
DROP TABLE IF EXISTS PONEY;
DROP TABLE IF EXISTS PERSONNE;
DROP TABLE IF EXISTS COURS_PROGRAMME;

-- Création de la table COURS_PROGRAMME
CREATE TABLE COURS_PROGRAMME (
  id_cp INTEGER PRIMARY KEY AUTOINCREMENT, -- MODIF : Remplacement de INT AUTO_INCREMENT par INTEGER PRIMARY KEY AUTOINCREMENT
  nom_cours TEXT NOT NULL,
  niveau INTEGER NOT NULL,
  duree INTEGER NOT NULL,
  heure TEXT NOT NULL, -- MODIF : Stockage des heures sous forme de texte (HH:MM)
  jour TEXT NOT NULL CHECK (jour IN ('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')),
  Ddd DATE NOT NULL,
  Ddf DATE NOT NULL,
  nb_personnes_max INTEGER NOT NULL
);

-- Création de la table PERSONNE
CREATE TABLE PERSONNE (
  id_p INTEGER PRIMARY KEY AUTOINCREMENT, -- MODIF : Remplacement de INT AUTO_INCREMENT par INTEGER PRIMARY KEY AUTOINCREMENT
  nom TEXT NOT NULL,
  prenom TEXT NOT NULL,
  adresse TEXT NOT NULL,
  telephone TEXT NOT NULL,
  email TEXT NOT NULL,
  experience TEXT,
  salaire REAL, -- MODIF : Utilisation de REAL pour des valeurs flottantes
  poids REAL CHECK (poids > 9 AND poids < 51),
  cotisation REAL,
  date_inscription DATE NOT NULL,
  niveau INTEGER CHECK (niveau > 0 AND niveau < 6)
);

-- Création de la table PONEY
CREATE TABLE PONEY (
  id INTEGER PRIMARY KEY AUTOINCREMENT, -- MODIF : Remplacement de INT AUTO_INCREMENT par INTEGER PRIMARY KEY AUTOINCREMENT
  nom TEXT NOT NULL,
  age INTEGER CHECK(age > 0 AND age < 26),
  poids_max REAL CHECK(poids_max > 9 AND poids_max < 51)
);

-- Création de la table COURS_REALISE
CREATE TABLE COURS_REALISE (
  id_cours INTEGER NOT NULL,
  id_moniteur INTEGER NOT NULL,
  dateR TEXT NOT NULL, -- MODIF : Utilisation de TEXT pour stocker date et heure au format ISO8601
  PRIMARY KEY (id_cours, dateR),
  FOREIGN KEY (id_cours) REFERENCES COURS_PROGRAMME (id_cp),
  FOREIGN KEY (id_moniteur) REFERENCES PERSONNE (id_p)
);

-- Création de la table RESERVER
CREATE TABLE RESERVER (
  id_client INTEGER NOT NULL,
  id_poney INTEGER NOT NULL,
  id_cours INTEGER NOT NULL,
  dateR TEXT NOT NULL, -- MODIF : Utilisation de TEXT pour date et heure
  PRIMARY KEY (id_client, id_poney, id_cours, dateR),
  FOREIGN KEY (id_client) REFERENCES PERSONNE (id_p),
  FOREIGN KEY (id_poney) REFERENCES PONEY (id),
  FOREIGN KEY (id_cours, dateR) REFERENCES COURS_REALISE (id_cours, dateR)
);

-- Trigger : Vérifier le poids max du poney
CREATE TRIGGER VerifierPoidsPoney
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, 'Erreur : la personne ne peut pas monter sur ce poney, son poids dépasse le poids maximum.')
  WHERE (SELECT poids FROM PERSONNE WHERE id_p = NEW.id_client) >
        (SELECT poids_max FROM PONEY WHERE id = NEW.id_poney);
END;

-- Trigger : Vérifier le repos des poneys
CREATE TRIGGER VerifierReposPoney
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, 'Le poney doit avoir une heure de repos après deux heures de travail consécutif.')
  WHERE (SELECT IFNULL(SUM(C.duree), 0) -- MODIF : Utilisation de IFNULL pour gérer les valeurs NULL
         FROM RESERVER R
         JOIN COURS_PROGRAMME C ON R.id_cours = C.id_cp
         WHERE R.id_poney = NEW.id_poney
           AND datetime(R.dateR) > datetime(NEW.dateR, '-2 hours')
           AND datetime(R.dateR) < datetime(NEW.dateR)) > 2;
END;

-- Trigger : Vérifier que la date du cours réalisé est dans la période programmée
CREATE TRIGGER VerifierDatePeriode
BEFORE INSERT ON COURS_REALISE
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, "Erreur : la date du cours est en dehors des dates programmées.")
  WHERE NOT EXISTS (
    SELECT 1
    FROM COURS_PROGRAMME
    WHERE id_cp = NEW.id_cours
      AND date(NEW.dateR) BETWEEN date(Ddd) AND date(Ddf)
      AND time(NEW.dateR) = heure -- MODIF : Conversion explicite en time pour comparaison
  );
END;

-- Trigger : Vérifier le nombre maximal de participants
CREATE TRIGGER VerifierNbPersonnesMax
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, 'Erreur : le nombre maximal de personnes pour ce cours a été atteint.')
  WHERE (SELECT COUNT(*)
         FROM RESERVER
         WHERE id_cours = NEW.id_cours
           AND dateR = NEW.dateR) >= (SELECT nb_personnes_max FROM COURS_PROGRAMME WHERE id_cp = NEW.id_cours);
END;

-- Trigger : Vérifier la compatibilité du niveau de la personne avec le cours
CREATE TRIGGER VerifierNiveauPersonne
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, 'Erreur : le niveau de la personne est inférieur au niveau du cours.')
  WHERE (SELECT niveau FROM PERSONNE WHERE id_p = NEW.id_client) <
        (SELECT niveau FROM COURS_PROGRAMME WHERE id_cp = NEW.id_cours);
END;

-- Trigger : Vérifier si le poney est déjà occupé à la même heure
CREATE TRIGGER VerifierPoneyOccupe
BEFORE INSERT ON RESERVER
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, 'Erreur : Le poney est déjà réservé pour un autre cours à cette heure.')
  WHERE EXISTS (
    SELECT 1
    FROM RESERVER
    WHERE id_poney = NEW.id_poney
      AND dateR = NEW.dateR
  );
END;

-- Trigger : Vérifier si le moniteur est déjà occupé à la même heure
CREATE TRIGGER VerifierMoniteurOccupe
BEFORE INSERT ON COURS_REALISE
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, 'Erreur : Le moniteur est déjà occupé pour un autre cours à cette heure.')
  WHERE EXISTS (
    SELECT 1
    FROM COURS_REALISE
    WHERE id_moniteur = NEW.id_moniteur
      AND dateR = NEW.dateR
  );
END;

-- Trigger : Vérifier si la personne réalisant le cours est un moniteur
CREATE TRIGGER VerifierEstMoniteur
BEFORE INSERT ON COURS_REALISE
FOR EACH ROW
BEGIN
  SELECT RAISE(FAIL, "Erreur : la personne n'est pas un moniteur.")
  WHERE NOT EXISTS (
    SELECT 1
    FROM PERSONNE
    WHERE id_p = NEW.id_moniteur
      AND salaire IS NOT NULL -- MODIF : Vérification de salaire pour confirmer moniteur
  );
END;
