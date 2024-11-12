--INSERTIONS DE LA BASE DE DONNEES--

-----------------------------------------------------------------

-- Insertion dans COURS_PROGRAMME
INSERT INTO COURS_PROGRAMME (id_cp, nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES 
(1, 'C1', 1, 1, '10:00:00', 'Lundi', '2020-01-01', '2020-12-31', 10),
(2, 'C2', 1, 1, '11:00:00', 'Mardi', '2020-01-01', '2020-12-31', 10),
(3, 'C3', 1, 2, '12:00:00', 'Mercredi', '2020-01-01', '2020-12-31', 10),
(4, 'C4', 5, 2, '12:00:00', 'Mercredi', '2020-01-01', '2020-12-31', 10);

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
INSERT INTO PONEY (id, nom, age, poids_max)
VALUES 
(1, 'Tornado', 7, 75.0),  -- Poney avec poids max de 75 kg
(2, 'Fury', 5, 90.0);      -- Poney avec poids max de 90 kg

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
VALUES (2, 1, 2, '2023-10-02 11:00:00');


-- Insertion dans RESERVER : Test échoué car niveau inférieur au niveau du cours
INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
VALUES (1, 1, 4, '2023-10-02 11:00:00');


 INSERT INTO RESERVER (id_personne, id_poney, id_cours, dateR)
 VALUES (1, 1, 1, '2023-10-01 13:00:00');  -- Tentative d'une réservation 30 minutes après le dernier cours