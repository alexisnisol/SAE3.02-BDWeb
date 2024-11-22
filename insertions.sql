-- Clear tables
DELETE FROM RESERVER;
DELETE FROM COURS_REALISE;
DELETE FROM COURS_PROGRAMME;
DELETE FROM PERSONNE;
DELETE FROM PONEY;

ALTER TABLE COURS_PROGRAMME AUTO_INCREMENT = 1;

-- Insertion dans PERSONNE

-- Poids limite mais valide : Client
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (1, 'Dupont', 'Alice', '123 Rue de Paris', '0102030405', 'alice@example.com', NULL, NULL, 50.0, NULL, '2023-10-01', 1);

-- Valide : Client
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (2, 'Martin', 'Bob', '456 Rue de Lyon', '0105060708', 'bob@example.com', 'Intermediaire', NULL, 15.0, NULL, '2023-11-01', 3);

-- Poids trop important
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (3, 'Invalide', 'Invalide', 'Invalide', 'Invalide', 'Invalide@example.com', NULL, NULL, 75, NULL, '2023-12-01', 2);

-- Niveau trop important
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (3, 'Invalide', 'Invalide', 'Invalide', 'Invalide', 'Invalide@example.com', NULL, NULL, 45, NULL, '2023-12-01', 66);  

-- Valide : Moniteur
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (3, 'Durand', 'Claire', '789 Rue de Marseille', '0102030406', 'claire@example.com', 'Experienced', 3000, 10.0, NULL, '2023-12-01', 2);

-- Insertion dans PONEY

-- Valide
INSERT INTO PONEY (id, nom, age, poids_max)
VALUES 
  (1, 'Spirit', 10, 45.0);

-- Valide
INSERT INTO PONEY (id, nom, age, poids_max)
VALUES 
  (2, 'Black Beauty', 12, 40.0);

-- Poids trop important
INSERT INTO PONEY (id, nom, age, poids_max)
VALUES 
  (3, 'Invalide', 10, 80.0);

-- Age trop important
INSERT INTO PONEY (id, nom, age, poids_max)
VALUES 
  (3, 'Invalide', 35, 30.0);


-- Test inserting valid data
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES ('Cours de saut', 3, 1, '10:00:00', 'Lundi', '2024-11-21', '2024-12-21', 5);

-- Test inserting invalid data for niveau (should fail)
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES ('Cours de saut1', 6, 1, '10:00:00', 'Lundi', '2024-11-21', '2024-12-21', 5);

-- Test inserting invalid data for duree (should fail)
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES ('Cours de saut2', 3, 3, '10:00:00', 'Lundi', '2024-11-21', '2024-12-21', 5);

-- Test inserting invalid data for heure (should fail)
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES ('Cours de saut3', 3, 1, '25:00:00', 'Lundi', '2024-11-21', '2024-12-21', 5);

-- Test inserting invalid data for jour (should fail)
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES ('Cours de saut4', 3, 1, '10:00:00', 'Funday', '2024-11-21', '2024-12-21', 5);

-- Test inserting invalid data for nb_personnes_max (should fail)
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES ('Cours de saut5', 3, 1, '10:00:00', 'Lundi', '2024-11-21', '2024-12-21', 15);

-- Test inserting valid data
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES 
  ('Cours Debutant', 1, 2, '10:00:00', 'Lundi', '2024-01-01', '2024-01-07', 5),
  ('Cours Avance', 4, 1, '14:00:00', 'Mardi', '2024-01-08', '2024-01-14', 3),
  ('Cours Expert', 5, 1, '09:00:00', 'Mercredi', '2024-01-15', '2024-01-21', 5);


-- Test inserting valid data
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (1, 3, '2024-01-01 10:00:00'), -- err
  (2, 3, '2024-01-02 14:00:00'), -- err
  (3, 3, '2024-01-03 09:00:00'); -- err

-- Test inserting invalid data for id_cours (should fail)
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (8645, 3, '2024-01-01 10:00:00');

-- Test inserting invalid data for id_moniteur (should fail)
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (4, 1, '2024-01-01 10:00:00');

-- Test inserting invalid data for dateR (should fail)
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (4, 3, '2024-01-01 10:00:00');
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (4, 3, '2024-01-02 14:00:00');
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (4, 3, '2024-01-03 09:00:00');
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (4, 3, '2024-01-01 10:00:00');



-- Cas valide
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (2, 1, 1, '2024-01-01 10:00:00');

-- Cas non valide (poids de la personne superieur au poids max du poney)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (1, 2, 1, '2024-01-01 11:00:00');


-- Cas valide (le poney a eu du repos)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (2, 1, 2, '2024-01-02 14:00:00');

-- Cas non valide (le poney a travaille trop d'heures sans repos)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (2, 1, 1, '2024-01-02 15:00:00');


-- Cas valide (nb personnes dans la limite)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (2, 1, 1, '2024-01-03 10:00:00');

-- Cas non valide (depassement du nb_personnes_max)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (3, 2, 1, '2024-01-03 10:00:00');


-- Cas valide (niveau compatible)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (2, 2, 2, '2024-01-04 14:00:00');

-- Cas non valide (niveau de la personne insuffisant pour le cours)
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (1, 1, 2, '2024-01-04 15:00:00');
