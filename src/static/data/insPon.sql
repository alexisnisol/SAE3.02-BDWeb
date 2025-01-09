-- Clear tables
DELETE FROM RESERVER;
DELETE FROM COURS_REALISE;
DELETE FROM COURS_PROGRAMME;
DELETE FROM PERSONNE;
DELETE FROM PONEY;

-- Insertion dans PERSONNE

-- Poids limite mais valide : Client
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (1, 'Dupont', 'Alice', '123 Rue de Paris', '0102030405', 'alice@example.com', NULL, NULL, 20.0, NULL, '2023-10-01', 4);

-- Valide : Client
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (2, 'Martin', 'Bob', '456 Rue de Lyon', '0105060708', 'bob@example.com', 'Intermediaire', NULL, 15.0, NULL, '2023-11-01', 5);

-- Valide : Moniteur
INSERT INTO PERSONNE (id_p, nom, prenom, adresse, telephone, email, experience, salaire, poids, cotisation, date_inscription, niveau)
VALUES (3, 'Durand', 'Claire', '789 Rue de Marseille', '0102030406', 'claire@example.com', 'Experienced', 3000, 10.0, NULL, '2023-12-01', 4);

-- Insertion dans PONEY

-- Valide
INSERT INTO PONEY (id, nom, age, poids_max)
VALUES 
  (1, 'Spirit', 10, 45.0),
  (2, 'Black Beauty', 12, 40.0);

-- Insertion dans COURS_PROGRAMME
-- Ajout de cours pour la semaine du 9 décembre et autres périodes

INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, Ddd, Ddf, nb_personnes_max)
VALUES 
  ('Cours Debutant', 1, 2, '10:00:00', 'Lundi', '2024-12-09', '2024-12-15', 4),
  ('Cours Avancé', 3, 1, '14:00:00', 'Mardi', '2024-12-09', '2024-12-15', 3),
  ('Cours Expert', 5, 1, '09:00:00', 'Mercredi', '2024-12-09', '2024-12-15', 5),
  ('Cours de Dressage', 4, 2, '11:00:00', 'Jeudi', '2024-12-09', '2024-12-15', 2),
  ('Cours de Découverte', 1, 1, '10:00:00', 'Vendredi', '2024-12-09', '2024-12-15', 3),
  ('Cours de Saut', 3, 1, '10:00:00', 'Lundi', '2024-11-21', '2024-12-06', 5),
  ('Cours Longue Durée', 2, 2, '15:00:00', 'Samedi', '2024-12-16', '2024-12-31', 4),
  ('Cours de Dressage' , 2,1, '10:00:00', 'Lundi', '2025-01-01', '2025-06-31', 3),
  ('Cours Avancé', 3, 2, '14:00:00', 'Mardi', '2025-01-01', '2025-06-31', 3),
  ('Cours Debutant', 1, 1, '09:00:00', 'Mercredi', '2025-01-01', '2025-06-31', 5),
  ('Cours de Saut', 4, 2, '11:00:00', 'Jeudi', '2025-01-01', '2025-06-31', 2),
  ('Cours de Découverte', 1, 1, '10:00:00', 'Vendredi', '2025-01-01', '2025-06-31', 3),
  ('Cours Longue Durée', 2, 2, '15:00:00', 'Samedi', '2025-01-01', '2025-06-31', 4);

-- Insertion dans COURS_REALISE
-- Ajout des cours réalisés en fonction des plages

INSERT INTO COURS_REALISE (id_cours, id_moniteur, dateR)
VALUES 
  (1, 3, '2024-12-09 10:00:00'),
  (2, 3, '2024-12-10 14:00:00'),
  (3, 3, '2024-12-11 09:00:00'),
  (4, 3, '2024-12-12 11:00:00'),
  (5, 3, '2024-12-13 10:00:00'),
  (6, 3, '2024-12-02 10:00:00'), 
  (7, 3, '2024-12-16 15:00:00'), 
  (8, 3, '2025-01-01 10:00:00'),
  (9, 3, '2025-01-02 14:00:00'),
  (10, 3, '2025-01-03 09:00:00'),
  (11, 3, '2025-01-04 11:00:00'),
  (12, 3, '2025-01-05 10:00:00'),
  (13, 3, '2025-01-06 15:00:00');




INSERT INTO RESERVER (id_client, id_poney, id_cours, dateR)
VALUES 
  (2, 1, 1, '2024-12-09 10:00:00'),
  (1, 2, 2, '2024-12-10 14:00:00'),
  (2, 1, 3, '2024-12-11 09:00:00'),
  (1, 2, 4, '2024-12-12 11:00:00'), 
  (2, 1, 5, '2024-12-13 10:00:00'); 
  
