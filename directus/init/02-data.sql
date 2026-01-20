-- Données de test pour Directus

-- Spécialités
INSERT INTO specialites (id, libelle, description) VALUES
(1, 'médecine générale', 'Médecine Générale'),
(2, 'Imagerie médicale', 'radiologie, échographie, IRM'),
(3, 'pédiatrie', 'Maladies des enfants'),
(4, 'ophtalmologie', 'Maladies des yeux'),
(5, 'Dentiste', 'Bouche et dents')
ON CONFLICT (id) DO NOTHING;

-- Reset sequence
SELECT setval('specialites_id_seq', 5);

-- Structures
INSERT INTO structures (id, nom, adresse, ville, code_postal, telephone) VALUES
('3444bdd2-8783-3aed-9a5e-4d298d2a2d7c', 'Cabinet Bigot', '63, rue de Mercier', 'Paris', '75002', '01 02 03 04 05'),
('372c1de7-4d31-3d01-9c92-77e69467d4e3', 'Cabinet Toussaint Marechal', '290, rue Rossi', 'Joubert', '54543', '+33 03 83 56 78 98'),
('7db214d9-f415-3110-991b-ec5f9774b685', 'Urgences Marin', '96, rue Louis', 'Bourdon-les-Bains', '71344', '09 78 67 54 43'),
('255ecef6-14b9-3b5c-a40e-901665f4ed28', 'Pichon Santé', '93, impasse Alain Baron', 'Diaz-sur-Boulanger', '48987', '01 02 04 50 37')
ON CONFLICT (id) DO NOTHING;

-- Moyens de paiement
INSERT INTO moyens_paiement (id, libelle) VALUES
(1, 'carte bancaire'),
(2, 'espèces'),
(3, 'chèque'),
(4, 'virement'),
(5, 'carte vitale')
ON CONFLICT (id) DO NOTHING;

SELECT setval('moyens_paiement_id_seq', 5);

-- Praticiens
INSERT INTO praticiens (id, nom, prenom, ville, email, telephone, specialite_id, structure_id) VALUES
('8ae1400f-d46d-3b50-b356-269f776be532', 'Klein', 'Gabrielle', 'Paris', 'Gabrielle.Klein@live.com', '+33 (0)3 90 27 98 80', 1, '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c'),
('dada1285-f235-3ad2-bd7d-f58d68c30a73', 'Goncalves', 'Noël', 'Paris', 'Noel.Goncalves@free.fr', '+33 5 82 74 18 66', 2, '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c'),
('8236bcbf-4c06-3d0e-8ab0-c4964e02c4ea', 'Pichon', 'Arnaude', 'Paris', 'Arnaude.Pichon@yahoo.fr', '06 61 50 63 81', 4, '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c'),
('794f6ea3-9801-334b-ba82-4ac71f70f6d2', 'Marechal', 'Inès', 'Bourdon-les-Bains', 'Ines.Marechal@wanadoo.fr', '0469478837', 2, '7db214d9-f415-3110-991b-ec5f9774b685'),
('af7bb2f1-cc52-3388-b9bc-c0b89e7f4c5b', 'Dupont', 'Marie', 'Joubert', 'marie.dupont@gmail.com', '03 83 12 34 56', 3, '372c1de7-4d31-3d01-9c92-77e69467d4e3'),
('b994a36f-794f-3ddc-b267-99673661466d', 'Martin', 'Lucas', 'Paris', 'lucas.martin@hotmail.fr', '01 45 67 89 00', 1, NULL),
('51c6c5a5-0815-3ff1-b0e4-ac216319e526', 'Bernard', 'Sophie', 'Diaz-sur-Boulanger', 'sophie.bernard@outlook.com', '04 56 78 90 12', 5, '255ecef6-14b9-3b5c-a40e-901665f4ed28'),
('592692c8-4a8c-3f91-967b-fde67ebea54d', 'Moreau', 'Thomas', 'Bourdon-les-Bains', 'thomas.moreau@gmail.fr', '06 12 34 56 78', 4, '7db214d9-f415-3110-991b-ec5f9774b685')
ON CONFLICT (id) DO NOTHING;

-- Motifs de visite
INSERT INTO motifs_visite (id, libelle, specialite_id) VALUES
(1, 'Consultation générale', 1),
(2, 'Renouvellement ordonnance', 1),
(3, 'Vaccination', 1),
(4, 'Radiographie', 2),
(5, 'Échographie', 2),
(6, 'IRM', 2),
(7, 'Consultation pédiatrique', 3),
(8, 'Vaccination enfant', 3),
(9, 'Examen de la vue', 4),
(10, 'Prescription lunettes', 4),
(11, 'Détartrage', 5),
(12, 'Consultation dentaire', 5)
ON CONFLICT (id) DO NOTHING;

SELECT setval('motifs_visite_id_seq', 12);
