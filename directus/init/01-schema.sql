-- Script d'initialisation des collections pour Directus
-- Ces tables seront détectées automatiquement par Directus

-- Spécialités
CREATE TABLE IF NOT EXISTS specialites (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(48) NOT NULL,
    description TEXT
);

-- Structures
CREATE TABLE IF NOT EXISTS structures (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nom VARCHAR(48) NOT NULL,
    adresse TEXT NOT NULL,
    ville VARCHAR(128),
    code_postal VARCHAR(12),
    telephone VARCHAR(24)
);

-- Moyens de paiement
CREATE TABLE IF NOT EXISTS moyens_paiement (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(32) NOT NULL
);

-- Praticiens
CREATE TABLE IF NOT EXISTS praticiens (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nom VARCHAR(48) NOT NULL,
    prenom VARCHAR(48) NOT NULL,
    ville VARCHAR(48) NOT NULL,
    email VARCHAR(128) NOT NULL,
    telephone VARCHAR(24) NOT NULL,
    rpps_id VARCHAR(12),
    organisation BOOLEAN DEFAULT false,
    nouveau_patient BOOLEAN DEFAULT true,
    titre VARCHAR(8) DEFAULT 'Dr.',
    specialite_id INTEGER REFERENCES specialites(id),
    structure_id UUID REFERENCES structures(id)
);

-- Motifs de visite
CREATE TABLE IF NOT EXISTS motifs_visite (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(128) NOT NULL,
    specialite_id INTEGER REFERENCES specialites(id)
);

-- Tables de liaison Many-to-Many
CREATE TABLE IF NOT EXISTS praticiens_moyens_paiement (
    id SERIAL PRIMARY KEY,
    praticiens_id UUID REFERENCES praticiens(id) ON DELETE CASCADE,
    moyens_paiement_id INTEGER REFERENCES moyens_paiement(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS praticiens_motifs_visite (
    id SERIAL PRIMARY KEY,
    praticiens_id UUID REFERENCES praticiens(id) ON DELETE CASCADE,
    motifs_visite_id INTEGER REFERENCES motifs_visite(id) ON DELETE CASCADE
);
