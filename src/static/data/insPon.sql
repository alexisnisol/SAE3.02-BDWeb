-- Clear tables
DELETE FROM RESERVER;
DELETE FROM COURS_REALISE;
DELETE FROM COURS_PROGRAMME;
DELETE FROM PERSONNE;
DELETE FROM PONEY;

ALTER TABLE COURS_PROGRAMME AUTO_INCREMENT = 1;

-- Insertion dans PERSONNE

-- Poids limite mais valide : Client
INSERT INTO PERSONNE (nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES ('Dupont', 'Alice', '123 Rue de Paris', '0102030405', 'alice@example.com', NULL, NULL, 50.0, NULL, '2023-10-01', 1);

-- Valide : Client
INSERT INTO PERSONNE (nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES ('Martin', 'Bob', '456 Rue de Lyon', '0105060708', 'bob@example.com', 'Intermediaire', NULL, 15.0, NULL, '2023-11-01', 3);

-- Valide : Moniteur
INSERT INTO PERSONNE (nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES ('Durand', 'Claire', '789 Rue de Marseille', '0102030406', 'claire@example.com', 'Experienced', 3000, 10.0, NULL, '2023-12-01', 2);

-- Insertion dans PONEY

-- Valide
INSERT INTO PONEY (nom, age, poids_max)
VALUES
    ('Spirit', 10, 45.0);

-- Valide
INSERT INTO PONEY (nom, age, poids_max)
VALUES
    ('Black Beauty', 12, 40.0);

-- Test inserting valid data
INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES
    ('Cours Debutant', 1, 2, '10:00:00', 'Lundi', '2024-01-01', '2024-01-07', 2),
    ('Cours Avance', 2, 2, '14:00:00', 'Mardi', '2024-01-08', '2024-01-14', 3),
    ('Cours Expert', 3, 1, '09:00:00', 'Mercredi', '2024-01-15', '2024-01-21', 5),
    ('Cours de saut', 3, 1, '10:00:00', 'Lundi', '2024-11-21', '2024-12-21', 5),
    ('Cours Apprentissage', 1, 2, '10:00:00', 'Lundi', '2024-01-01', '2024-01-10', 5),
    ('Cours Apprentissage', 1, 2, '12:00:00', 'Lundi', '2024-01-01', '2024-01-10', 5),
    ('Cours Apprentissage', 1, 2, '13:00:00', 'Lundi', '2024-01-01', '2024-01-10', 5);

-- Test inserting valid data
INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES
    (1, 3, '2024-01-01 10:00:00'),
    (2, 3, '2024-01-09 14:00:00'),
    (3, 3, '2024-01-21 09:00:00'),
    (5, 3, '2024-01-09 10:00:00'),
    (6, 3, '2024-01-09 12:00:00'),
    (7, 3, '2024-01-09 13:00:00');


-- Cas valide
INSERT INTO RESERVER (id_client, id_poney, id_cours, dateR)
VALUES (2, 1, 1, '2024-01-01 10:00:00');

-- Cas valide (le poney a eu du repos)
INSERT INTO RESERVER (id_client, id_poney, id_cours, dateR)
VALUES (2, 1, 5, '2024-01-09 10:00:00');

-- Cas valide, l'heure de repos est respectee
INSERT INTO RESERVER (id_client, id_poney, id_cours, dateR)
VALUES (2, 1, 7, '2024-01-09 13:00:00');

-- Cas valide (nb personnes dans la limite)
INSERT INTO RESERVER (id_client, id_poney, id_cours, dateR)
VALUES (2, 2, 1, '2024-01-01 10:00:00');