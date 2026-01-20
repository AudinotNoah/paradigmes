<?php
/**
 * TP1 - Doctrine ORM
 * Toutes les réponses aux exercices
 */

use toubeelib\praticien\Entity\Specialite;
use toubeelib\praticien\Entity\Praticien;
use toubeelib\praticien\Entity\Structure;
use toubeelib\praticien\Entity\MotifVisite;
use toubeelib\praticien\Entity\MoyenPaiement;

$entityManager = require_once __DIR__ . '/../config/bootstrap.php';

echo "<h1>TP1 - Doctrine ORM - Réponses aux exercices</h1>";

// --- Exercice 1 : utilisation élémentaire ---
echo "<h2>Exercice 1 : Utilisation élémentaire</h2>";

// 1.1 Spécialité id=1 : id, libellé, description
echo "<h3>1.1 - Spécialité ID 1</h3>";
$specialite1 = $entityManager->find(Specialite::class, 1);
if ($specialite1) {
    echo "<pre>";
    echo "ID: " . $specialite1->getId() . "\n";
    echo "Libellé: " . $specialite1->getLibelle() . "\n";
    echo "Description: " . $specialite1->getDescription() . "\n";
    echo "</pre>";
}

// 1.2 Praticien id=8ae1400f-d46d-3b50-b356-269f776be532 : id, nom, prénom, ville, email, téléphone
echo "<h3>1.2 - Praticien ID 8ae1400f-d46d-3b50-b356-269f776be532</h3>";
$praticien = $entityManager->find(Praticien::class, '8ae1400f-d46d-3b50-b356-269f776be532');
if ($praticien) {
    echo "<pre>";
    echo "ID: " . $praticien->getId() . "\n";
    echo "Nom: " . $praticien->getNom() . "\n";
    echo "Prénom: " . $praticien->getPrenom() . "\n";
    echo "Ville: " . $praticien->getVille() . "\n";
    echo "Email: " . $praticien->getEmail() . "\n";
    echo "Téléphone: " . $praticien->getTelephone() . "\n";
    echo "</pre>";
}

// 1.3 Compléter avec spécialité et structure
echo "<h3>1.3 - Praticien avec spécialité et structure</h3>";
if ($praticien) {
    echo "<pre>";
    echo "Praticien: " . $praticien->getPrenom() . " " . $praticien->getNom() . "\n";
    echo "Spécialité: " . $praticien->getSpecialite()->getLibelle() . "\n";
    $structure = $praticien->getStructure();
    echo "Structure: " . ($structure ? $structure->getNom() : "Aucune") . "\n";
    echo "</pre>";
}

// 1.4 Structure id=3444bdd2-8783-3aed-9a5e-4d298d2a2d7c avec liste des praticiens
echo "<h3>1.4 - Structure avec liste des praticiens</h3>";
$structure = $entityManager->find(Structure::class, '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c');
if ($structure) {
    echo "<pre>";
    echo "Structure: " . $structure->getNom() . "\n";
    echo "Ville: " . $structure->getVille() . "\n";
    echo "\nPraticiens rattachés:\n";
    foreach ($structure->getPraticiens() as $p) {
        echo "  - " . $p->getTitre() . " " . $p->getPrenom() . " " . $p->getNom() . " (" . $p->getSpecialite()->getLibelle() . ")\n";
    }
    echo "</pre>";
}

// 1.5 Spécialité id=1 avec motifs de visite
echo "<h3>1.5 - Spécialité ID 1 avec motifs de visite</h3>";
if ($specialite1) {
    echo "<pre>";
    echo "Spécialité: " . $specialite1->getLibelle() . "\n";
    echo "\nMotifs de visite:\n";
    foreach ($specialite1->getMotifsVisite() as $motif) {
        echo "  - " . $motif->getLibelle() . "\n";
    }
    echo "</pre>";
}

// 1.6 Motifs de visite du praticien 8ae1400f...
echo "<h3>1.6 - Motifs de visite du praticien</h3>";
if ($praticien) {
    echo "<pre>";
    echo "Praticien: " . $praticien->getPrenom() . " " . $praticien->getNom() . "\n";
    echo "\nMotifs de visite:\n";
    foreach ($praticien->getMotifsVisite() as $motif) {
        echo "  - " . $motif->getLibelle() . "\n";
    }
    echo "</pre>";
}

// 1.7 Créer un praticien (spécialité: pédiatrie) et sauvegarder
echo "<h3>1.7 - Création d'un praticien (pédiatrie)</h3>";
$specialitePediatrie = $entityManager->getRepository(Specialite::class)
    ->findOneBy(['libelle' => 'pédiatrie']);

$nouveauPraticien = new Praticien();
$nouveauPraticien->setNom('Dupont');
$nouveauPraticien->setPrenom('Jean');
$nouveauPraticien->setVille('Nancy');
$nouveauPraticien->setEmail('jean.dupont@example.com');
$nouveauPraticien->setTelephone('03 83 00 00 00');
$nouveauPraticien->setSpecialite($specialitePediatrie);

$entityManager->persist($nouveauPraticien);
$entityManager->flush();

echo "<pre>";
echo "Nouveau praticien créé!\n";
echo "ID: " . $nouveauPraticien->getId() . "\n";
echo "Nom: " . $nouveauPraticien->getNom() . "\n";
echo "Prénom: " . $nouveauPraticien->getPrenom() . "\n";
echo "Spécialité: " . $nouveauPraticien->getSpecialite()->getLibelle() . "\n";
echo "</pre>";

$nouveauPraticienId = $nouveauPraticien->getId();

// 1.8 Modifier le praticien créé (structure, ville, motifs)
echo "<h3>1.8 - Modification du praticien</h3>";

// Rattacher à la structure Cabinet Bigot
$cabinetBigot = $entityManager->getRepository(Structure::class)
    ->findOneBy(['nom' => 'Cabinet Bigot']);
$nouveauPraticien->setStructure($cabinetBigot);

// Changer la ville
$nouveauPraticien->setVille('Paris');

// Ajouter des motifs de visite (de la pédiatrie)
$motifsVisite = $entityManager->getRepository(MotifVisite::class)
    ->findBy(['specialite' => $specialitePediatrie]);
foreach ($motifsVisite as $motif) {
    $nouveauPraticien->addMotifVisite($motif);
}

$entityManager->flush();

echo "<pre>";
echo "Praticien modifié!\n";
echo "Ville: " . $nouveauPraticien->getVille() . "\n";
echo "Structure: " . $nouveauPraticien->getStructure()->getNom() . "\n";
echo "Motifs de visite:\n";
foreach ($nouveauPraticien->getMotifsVisite() as $motif) {
    echo "  - " . $motif->getLibelle() . "\n";
}
echo "</pre>";

// 1.9 Supprimer ce praticien
echo "<h3>1.9 - Suppression du praticien</h3>";
$entityManager->remove($nouveauPraticien);
$entityManager->flush();
echo "<pre>Praticien supprimé avec succès!</pre>";

// --- Exercice 2 : requêtes avec conditions ---
echo "<h2>Exercice 2 : Requêtes avec conditions de sélection</h2>";

// 2.1 Praticien par email
echo "<h3>2.1 - Praticien par email</h3>";
$praticienEmail = $entityManager->getRepository(Praticien::class)
    ->findOneBy(['email' => 'Gabrielle.Klein@live.com']);
if ($praticienEmail) {
    echo "<pre>";
    echo "Nom: " . $praticienEmail->getNom() . "\n";
    echo "Prénom: " . $praticienEmail->getPrenom() . "\n";
    echo "Email: " . $praticienEmail->getEmail() . "\n";
    echo "Ville: " . $praticienEmail->getVille() . "\n";
    echo "</pre>";
}

// 2.2 Praticien Goncalves à Paris
echo "<h3>2.2 - Praticien Goncalves à Paris</h3>";
$goncalves = $entityManager->getRepository(Praticien::class)
    ->findOneBy(['nom' => 'Goncalves', 'ville' => 'Paris']);
if ($goncalves) {
    echo "<pre>";
    echo "Nom: " . $goncalves->getNom() . "\n";
    echo "Prénom: " . $goncalves->getPrenom() . "\n";
    echo "Ville: " . $goncalves->getVille() . "\n";
    echo "Email: " . $goncalves->getEmail() . "\n";
    echo "</pre>";
}

// 2.3 Spécialité pédiatrie avec praticiens
echo "<h3>2.3 - Spécialité pédiatrie avec praticiens</h3>";
$pediatrie = $entityManager->getRepository(Specialite::class)
    ->findOneBy(['libelle' => 'pédiatrie']);
if ($pediatrie) {
    echo "<pre>";
    echo "Spécialité: " . $pediatrie->getLibelle() . "\n";
    echo "Description: " . $pediatrie->getDescription() . "\n";
    echo "\nPraticiens:\n";
    foreach ($pediatrie->getPraticiens() as $p) {
        echo "  - " . $p->getPrenom() . " " . $p->getNom() . " (" . $p->getVille() . ")\n";
    }
    echo "</pre>";
}

// 2.4 Spécialités dont la description contient 'santé' (QueryBuilder)
echo "<h3>2.4 - Spécialités contenant 'santé' dans description</h3>";
// Note: Dans les données, il n'y a pas de spécialité avec "santé" dans la description
// On utilise un QueryBuilder pour la requête critères
$qb = $entityManager->createQueryBuilder();
$qb->select('s')
   ->from(Specialite::class, 's')
   ->where($qb->expr()->like('s.description', ':keyword'))
   ->setParameter('keyword', '%santé%');
$specialitesSante = $qb->getQuery()->getResult();

echo "<pre>";
if (count($specialitesSante) > 0) {
    foreach ($specialitesSante as $s) {
        echo "- " . $s->getLibelle() . ": " . $s->getDescription() . "\n";
    }
} else {
    echo "Aucune spécialité trouvée avec 'santé' dans la description.\n";
    echo "(Cherchons avec 'Médecine' à la place)\n\n";

    $qb2 = $entityManager->createQueryBuilder();
    $qb2->select('s')
       ->from(Specialite::class, 's')
       ->where($qb2->expr()->like('s.description', ':keyword'))
       ->setParameter('keyword', '%Médecine%');
    $specialitesMedecine = $qb2->getQuery()->getResult();

    foreach ($specialitesMedecine as $s) {
        echo "- " . $s->getLibelle() . ": " . $s->getDescription() . "\n";
    }
}
echo "</pre>";

// 2.5 Praticiens ophtalmologie à Paris (DQL)
echo "<h3>2.5 - Praticiens ophtalmologie à Paris</h3>";
$dql = "SELECT p FROM toubeelib\praticien\Entity\Praticien p
        JOIN p.specialite s
        WHERE s.libelle = :specialite AND p.ville = :ville";
$praticiensParis = $entityManager->createQuery($dql)
    ->setParameter('specialite', 'ophtalmologie')
    ->setParameter('ville', 'Paris')
    ->getResult();

echo "<pre>";
echo "Praticiens en ophtalmologie à Paris:\n";
foreach ($praticiensParis as $p) {
    echo "  - " . $p->getPrenom() . " " . $p->getNom() . " (" . $p->getEmail() . ")\n";
}
echo "</pre>";

// --- Exercice 3 : repository et DQL ---
echo "<h2>Exercice 3 : Repository et DQL</h2>";

// 3.1 Spécialités contenant un mot-clé
echo "<h3>3.1 - Spécialités par mot-clé (exemple: 'méd')</h3>";
$specialiteRepo = $entityManager->getRepository(Specialite::class);
$specialitesKeyword = $specialiteRepo->findByKeyword('méd');
echo "<pre>";
echo "Spécialités contenant 'méd':\n";
foreach ($specialitesKeyword as $s) {
    echo "  - " . $s->getLibelle() . ": " . $s->getDescription() . "\n";
}
echo "</pre>";

// 3.2 Praticiens dont la spécialité contient un mot-clé
echo "<h3>3.2 - Praticiens par mot-clé dans spécialité (exemple: 'radio')</h3>";
$praticienRepo = $entityManager->getRepository(Praticien::class);
$praticiensSpecKeyword = $praticienRepo->findBySpecialiteKeyword('radio');
echo "<pre>";
echo "Praticiens dont la spécialité contient 'radio':\n";
foreach ($praticiensSpecKeyword as $p) {
    echo "  - " . $p->getPrenom() . " " . $p->getNom() . " (" . $p->getSpecialite()->getLibelle() . ")\n";
}
echo "</pre>";

// 3.3 Praticiens d'une spécialité acceptant un moyen de paiement
echo "<h3>3.3 - Praticiens par spécialité et moyen de paiement</h3>";
$praticiensMoyenPaiement = $praticienRepo->findBySpecialiteAndMoyenPaiement('médecine générale', 'carte bancaire');
echo "<pre>";
echo "Praticiens en médecine générale acceptant la carte bancaire:\n";
foreach ($praticiensMoyenPaiement as $p) {
    echo "  - " . $p->getPrenom() . " " . $p->getNom() . " (" . $p->getVille() . ")\n";
}
echo "</pre>";

echo "<hr><p><strong>Fin des exercices TP1</strong></p>";
