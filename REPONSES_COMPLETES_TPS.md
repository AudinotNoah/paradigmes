# Nouveaux Paradigmes de Bases de Données
## Réponses Complètes aux TPs - Guide Complet

**IUT Nancy Charlemagne - BUT 3 Informatique DWM**

---

# Table des matières

1. [Introduction aux nouveaux paradigmes](#introduction)
2. [TP1 : Doctrine ORM](#tp1)
3. [TP2 : MongoDB](#tp2)
4. [TP3 : Directus REST API](#tp3)
5. [TP4 : GraphQL](#tp4)

---

<a name="introduction"></a>
# Introduction aux Nouveaux Paradigmes de Bases de Données

## Qu'est-ce qu'un paradigme de base de données ?

Un paradigme de base de données est une approche conceptuelle pour stocker, organiser et accéder aux données. Traditionnellement, les bases de données relationnelles (SQL) dominaient, mais de nouveaux paradigmes sont apparus pour répondre à des besoins spécifiques.

### Les 4 paradigmes étudiés :

| Paradigme | Technologie | Type | Cas d'usage |
|-----------|-------------|------|-------------|
| ORM | Doctrine | Relationnel + Objet | Applications PHP avec BDD SQL |
| NoSQL | MongoDB | Document | Données semi-structurées, flexibilité |
| Headless CMS | Directus | REST API | Backend-as-a-Service |
| Query Language | GraphQL | API | Requêtes flexibles, frontend-driven |

---

<a name="tp1"></a>
# TP1 : Doctrine ORM (Object-Relational Mapping)

## C'est quoi Doctrine ?

**Doctrine** est un ORM (Object-Relational Mapping) pour PHP. Il fait le pont entre le monde **orienté objet** (classes PHP) et le monde **relationnel** (tables SQL).

### Pourquoi utiliser un ORM ?

1. **Abstraction** : On manipule des objets PHP, pas du SQL brut
2. **Sécurité** : Protection automatique contre les injections SQL
3. **Portabilité** : Code indépendant du SGBD (MySQL, PostgreSQL, etc.)
4. **Productivité** : Moins de code à écrire, plus de fonctionnalités

### Concepts clés

| Concept | Définition |
|---------|------------|
| **Entity** | Classe PHP mappée sur une table SQL |
| **Repository** | Classe pour requêter les entités |
| **EntityManager** | Gestionnaire central pour les opérations CRUD |
| **DQL** | Doctrine Query Language (SQL orienté objet) |

---

## Configuration de l'environnement

### Structure du projet
```
pratilib.doct.squelette/
├── config/
│   └── bootstrap.php      # Configuration Doctrine
├── src/
│   ├── Entity/            # Les entités (classes PHP)
│   │   ├── Specialite.php
│   │   ├── Praticien.php
│   │   ├── Structure.php
│   │   ├── MotifVisite.php
│   │   └── MoyenPaiement.php
│   └── Repository/        # Les repositories
│       ├── SpecialiteRepository.php
│       └── PraticienRepository.php
├── public/
│   └── index.php          # Point d'entrée
└── docker-compose.yml     # Services Docker
```

### Démarrage
```bash
# 1. Démarrer les services
docker-compose up -d

# 2. Installer les dépendances (dans le conteneur PHP)
docker exec -it <container> composer install

# 3. Créer le schéma de base de données
docker exec -it <container_postgres> psql -U prati -d prati -f /var/sql/prati.schema.sql
docker exec -it <container_postgres> psql -U prati -d prati -f /var/sql/prati.data.sql

# 4. Accéder à l'application
# http://localhost:3080
```

---

## Exercice 1 : Utilisation élémentaire

### 1.1 Afficher une spécialité par ID

**Objectif** : Récupérer une entité par son identifiant primaire.

```php
// Récupérer l'EntityManager
$entityManager = require_once __DIR__ . '/../config/bootstrap.php';

// Utiliser find() pour récupérer par ID
$specialite = $entityManager->find(Specialite::class, 1);

// Afficher les propriétés
echo "ID: " . $specialite->getId();           // 1
echo "Libellé: " . $specialite->getLibelle(); // médecine générale
echo "Description: " . $specialite->getDescription(); // Médecine Générale
```

**Explication** : `find(Classe, id)` est la méthode la plus simple pour récupérer une entité par sa clé primaire.

---

### 1.2 Afficher un praticien par UUID

```php
$praticien = $entityManager->find(
    Praticien::class,
    '8ae1400f-d46d-3b50-b356-269f776be532'
);

echo "Nom: " . $praticien->getNom();       // Klein
echo "Prénom: " . $praticien->getPrenom(); // Gabrielle
echo "Ville: " . $praticien->getVille();   // Paris
echo "Email: " . $praticien->getEmail();   // Gabrielle.Klein@live.com
echo "Téléphone: " . $praticien->getTelephone();
```

---

### 1.3 Relations : Spécialité et Structure

```php
// Les relations sont chargées automatiquement (lazy loading)
echo "Spécialité: " . $praticien->getSpecialite()->getLibelle();
// médecine générale

$structure = $praticien->getStructure();
echo "Structure: " . ($structure ? $structure->getNom() : "Aucune");
// Cabinet Bigot
```

**Concept important** : Doctrine charge les relations à la demande (lazy loading). Quand on appelle `getSpecialite()`, une requête SQL est exécutée automatiquement.

---

### 1.4 Collection de praticiens d'une structure

```php
$structure = $entityManager->find(
    Structure::class,
    '3444bdd2-8783-3aed-9a5e-4d298d2a2d7c'
);

echo "Structure: " . $structure->getNom(); // Cabinet Bigot
echo "Ville: " . $structure->getVille();   // Paris

// getPraticiens() retourne une Collection
foreach ($structure->getPraticiens() as $p) {
    echo $p->getPrenom() . " " . $p->getNom();
}
```

---

### 1.5 - 1.6 Relations One-to-Many et Many-to-Many

```php
// Motifs de visite d'une spécialité (OneToMany)
foreach ($specialite->getMotifsVisite() as $motif) {
    echo $motif->getLibelle();
}

// Motifs de visite d'un praticien (ManyToMany)
foreach ($praticien->getMotifsVisite() as $motif) {
    echo $motif->getLibelle();
}
```

---

### 1.7 Créer un praticien (INSERT)

```php
// Trouver la spécialité pédiatrie
$pediatrie = $entityManager->getRepository(Specialite::class)
    ->findOneBy(['libelle' => 'pédiatrie']);

// Créer le praticien
$nouveauPraticien = new Praticien();
$nouveauPraticien->setNom('Dupont');
$nouveauPraticien->setPrenom('Jean');
$nouveauPraticien->setVille('Nancy');
$nouveauPraticien->setEmail('jean.dupont@example.com');
$nouveauPraticien->setTelephone('03 83 00 00 00');
$nouveauPraticien->setSpecialite($pediatrie);

// Persister = préparer pour l'insertion
$entityManager->persist($nouveauPraticien);

// Flush = exécuter les requêtes SQL
$entityManager->flush();

echo "ID créé: " . $nouveauPraticien->getId();
```

**Points clés** :
- `persist()` : Indique à Doctrine de suivre l'objet
- `flush()` : Exécute réellement les requêtes SQL
- L'UUID est généré automatiquement dans le constructeur

---

### 1.8 Modifier un praticien (UPDATE)

```php
// Rattacher à une structure
$cabinetBigot = $entityManager->getRepository(Structure::class)
    ->findOneBy(['nom' => 'Cabinet Bigot']);
$nouveauPraticien->setStructure($cabinetBigot);

// Changer la ville
$nouveauPraticien->setVille('Paris');

// Ajouter des motifs de visite
$motifsVisite = $entityManager->getRepository(MotifVisite::class)
    ->findBy(['specialite' => $pediatrie]);
foreach ($motifsVisite as $motif) {
    $nouveauPraticien->addMotifVisite($motif);
}

// Sauvegarder (pas besoin de persist() car l'objet est déjà géré)
$entityManager->flush();
```

---

### 1.9 Supprimer un praticien (DELETE)

```php
$entityManager->remove($nouveauPraticien);
$entityManager->flush();
```

---

## Exercice 2 : Requêtes avec conditions

### 2.1 Recherche par critère simple

```php
// findOneBy avec un critère
$praticien = $entityManager->getRepository(Praticien::class)
    ->findOneBy(['email' => 'Gabrielle.Klein@live.com']);
```

### 2.2 Recherche multi-critères

```php
// findOneBy avec plusieurs critères
$goncalves = $entityManager->getRepository(Praticien::class)
    ->findOneBy(['nom' => 'Goncalves', 'ville' => 'Paris']);
```

### 2.3 Récupérer avec relation

```php
$pediatrie = $entityManager->getRepository(Specialite::class)
    ->findOneBy(['libelle' => 'pédiatrie']);

// Les praticiens sont accessibles via la relation
foreach ($pediatrie->getPraticiens() as $p) {
    echo $p->getPrenom() . " " . $p->getNom();
}
```

### 2.4 Requête avec LIKE (QueryBuilder)

```php
$qb = $entityManager->createQueryBuilder();
$qb->select('s')
   ->from(Specialite::class, 's')
   ->where($qb->expr()->like('s.description', ':keyword'))
   ->setParameter('keyword', '%santé%');

$specialites = $qb->getQuery()->getResult();
```

### 2.5 Requête avec jointure

```php
$dql = "SELECT p FROM toubeelib\praticien\Entity\Praticien p
        JOIN p.specialite s
        WHERE s.libelle = :specialite AND p.ville = :ville";

$praticiens = $entityManager->createQuery($dql)
    ->setParameter('specialite', 'ophtalmologie')
    ->setParameter('ville', 'Paris')
    ->getResult();
```

---

## Exercice 3 : Repository et DQL

### 3.1 Méthode repository - Recherche par mot-clé

```php
// Dans SpecialiteRepository.php
public function findByKeyword(string $keyword): array
{
    $dql = "SELECT s FROM toubeelib\praticien\Entity\Specialite s
            WHERE s.libelle LIKE :keyword OR s.description LIKE :keyword";

    return $this->getEntityManager()
        ->createQuery($dql)
        ->setParameter('keyword', '%' . $keyword . '%')
        ->getResult();
}

// Utilisation
$specialites = $specialiteRepo->findByKeyword('méd');
```

### 3.2 Praticiens par mot-clé dans spécialité

```php
// Dans PraticienRepository.php
public function findBySpecialiteKeyword(string $keyword): array
{
    $dql = "SELECT p FROM toubeelib\praticien\Entity\Praticien p
            JOIN p.specialite s
            WHERE s.libelle LIKE :keyword OR s.description LIKE :keyword";

    return $this->getEntityManager()
        ->createQuery($dql)
        ->setParameter('keyword', '%' . $keyword . '%')
        ->getResult();
}
```

### 3.3 Praticiens par spécialité et moyen de paiement

```php
public function findBySpecialiteAndMoyenPaiement(
    string $specialiteLibelle,
    string $moyenLibelle
): array {
    $dql = "SELECT p FROM toubeelib\praticien\Entity\Praticien p
            JOIN p.specialite s
            JOIN p.moyensPaiement m
            WHERE s.libelle = :specialite AND m.libelle = :moyen";

    return $this->getEntityManager()
        ->createQuery($dql)
        ->setParameter('specialite', $specialiteLibelle)
        ->setParameter('moyen', $moyenLibelle)
        ->getResult();
}
```

---

<a name="tp2"></a>
# TP2 : MongoDB (Base NoSQL Document)

## C'est quoi MongoDB ?

**MongoDB** est une base de données **NoSQL orientée documents**. Au lieu de tables avec des lignes, on a des **collections** avec des **documents JSON**.

### Différences SQL vs MongoDB

| SQL (PostgreSQL) | MongoDB |
|-----------------|---------|
| Base de données | Base de données |
| Table | Collection |
| Ligne | Document |
| Colonne | Champ |
| Jointure | Embedded documents ou références |
| Schéma fixe | Schéma flexible |

### Avantages de MongoDB

1. **Flexibilité** : Pas de schéma rigide, documents variables
2. **Performance** : Lectures rapides pour données imbriquées
3. **Scalabilité** : Conçu pour le clustering horizontal
4. **JSON natif** : Parfait pour les APIs web

---

## Configuration de l'environnement

```bash
# Démarrer les services
docker-compose up -d

# Importer les données
docker exec -it <mongo_container> mongoimport \
    --db chopizza --collection produits --jsonArray < /var/data/pizzashop.produits.json

docker exec -it <mongo_container> mongoimport \
    --db chopizza --collection recettes --jsonArray < /var/data/pizzashop.recettes.json
```

---

## Section 1 : Requêtes MongoShell

### 1.1 Liste de tous les produits
```javascript
db.produits.find()
```

### 1.2 Compter les produits
```javascript
db.produits.countDocuments()
// Résultat: 10
```

### 1.3 Tri par numéro décroissant
```javascript
db.produits.find().sort({ numero: -1 })
```

### 1.4 Recherche par libellé exact
```javascript
db.produits.findOne({ libelle: "Margherita" })
```

### 1.5 Filtrer par catégorie
```javascript
db.produits.find({ categorie: "Boissons" })
```

### 1.6 Projection (sélection de champs)
```javascript
db.produits.find({}, {
    categorie: 1,
    numero: 1,
    libelle: 1,
    _id: 0
})
```

### 1.7 Projection avec sous-documents
```javascript
db.produits.find({}, {
    categorie: 1,
    numero: 1,
    libelle: 1,
    tarifs: 1,
    _id: 0
})
```

### 1.8 Recherche dans tableau imbriqué
```javascript
// Produits avec AU MOINS UN tarif < 8.0
db.produits.find({ "tarifs.tarif": { $lt: 8.0 } })
```

### 1.9 $elemMatch pour conditions multiples
```javascript
// Produits dont le tarif GRANDE est < 8.0
db.produits.find({
    tarifs: {
        $elemMatch: {
            taille: "grande",
            tarif: { $lt: 8.0 }
        }
    }
})
```

### 1.10 Insertion d'un document
```javascript
db.produits.insertOne({
    numero: 11,
    libelle: "Calzone",
    description: "Pizza fermée...",
    image: "https://example.com/calzone.png",
    categorie: "Pizzas",
    tarifs: [
        { taille: "normale", tarif: 10.99 },
        { taille: "grande", tarif: 13.99 }
    ],
    recettes: []
})
```

### 1.11 Jointure avec aggregation
```javascript
db.produits.aggregate([
    { $match: { numero: 1 } },
    { $lookup: {
        from: "recettes",
        localField: "recettes",
        foreignField: "_id",
        as: "recettes_details"
    }},
    { $project: {
        libelle: 1,
        "recettes_details.nom": 1,
        "recettes_details.difficulte": 1
    }}
])
```

---

## Section 2 : Requêtes PHP

### 2.1 Liste des produits
```php
$produits = $db->produits->find([], [
    'projection' => ['numero' => 1, 'categorie' => 1, 'libelle' => 1]
]);

foreach ($produits as $produit) {
    echo $produit['numero'] . " - " . $produit['libelle'];
}
```

### 2.2 Détails d'un produit
```php
$produit = $db->produits->findOne(['numero' => 6]);

echo "Libellé: " . $produit['libelle'];
echo "Catégorie: " . $produit['categorie'];
foreach ($produit['tarifs'] as $tarif) {
    echo $tarif['taille'] . ": " . $tarif['tarif'] . " €";
}
```

### 2.3 $elemMatch en PHP
```php
$produits = $db->produits->find([
    'tarifs' => [
        '$elemMatch' => [
            'taille' => 'normale',
            'tarif' => ['$lte' => 3.0]
        ]
    ]
]);
```

### 2.4 $size pour compter les éléments
```php
$produits = $db->produits->find([
    'recettes' => ['$size' => 4]
]);
```

### 2.5 Jointure manuelle en PHP
```php
$produit = $db->produits->findOne(['numero' => 6]);

foreach ($produit['recettes'] as $recetteRef) {
    $recette = $db->recettes->findOne(['_id' => $recetteRef]);
    echo $recette['nom'] . " (Difficulté: " . $recette['difficulte'] . ")";
}
```

### 2.6 Fonction réutilisable
```php
function getProductInfo(MongoDB\Database $db, int $numero, string $taille): ?array
{
    $produit = $db->produits->findOne(['numero' => $numero]);

    if (!$produit) return null;

    $tarifProduit = null;
    foreach ($produit['tarifs'] as $t) {
        if ($t['taille'] === $taille) {
            $tarifProduit = $t['tarif'];
            break;
        }
    }

    return [
        'numero' => $produit['numero'],
        'libelle' => $produit['libelle'],
        'categorie' => $produit['categorie'],
        'taille' => $taille,
        'tarif' => $tarifProduit
    ];
}

// Utilisation
$info = getProductInfo($db, 1, 'grande');
echo json_encode($info, JSON_PRETTY_PRINT);
```

---

<a name="tp3"></a>
# TP3 : Directus REST API

## C'est quoi Directus ?

**Directus** est un **Headless CMS** open-source. Il transforme automatiquement n'importe quelle base SQL en une **API REST et GraphQL**.

### Avantages

1. **Backend instantané** : API générée automatiquement
2. **Interface admin** : Gestion des données via UI
3. **Permissions** : Système de rôles granulaire
4. **Flexibilité** : S'adapte à n'importe quel schéma

---

## Configuration

### docker-compose.yml
```yaml
services:
  directus:
    image: directus/directus:latest
    ports:
      - 8055:8055
    environment:
      ADMIN_EMAIL: 'admin@example.com'
      ADMIN_PASSWORD: 'd1r3ctu5'
```

### Démarrage
```bash
docker-compose up -d
# Accéder: http://localhost:8055
```

---

## Requêtes REST API

### Base URL : `http://localhost:8055`

### 1. Liste des praticiens
```http
GET /items/praticiens
```

### 2. Un item par ID
```http
GET /items/specialites/2
```

### 3. Sélection de champs (fields)
```http
GET /items/specialites/2?fields=libelle
```

### 4. Relations imbriquées
```http
GET /items/praticiens/ID?fields=*,specialite.libelle
```

### 5. Relations inverses
```http
GET /items/structures/ID?fields=nom,ville,praticiens.nom,praticiens.prenom
```

### 6. Relations profondes
```http
GET /items/structures/ID?fields=nom,ville,praticiens.nom,praticiens.prenom,praticiens.specialite.libelle
```

### 7. Filtrage avec _contains
```http
GET /items/structures?filter[ville][_contains]=sur&fields=nom,ville,praticiens.*
```

---

<a name="tp4"></a>
# TP4 : GraphQL

## C'est quoi GraphQL ?

**GraphQL** est un **langage de requête pour API** développé par Facebook. Contrairement à REST où le serveur décide des données retournées, avec GraphQL c'est le **client qui spécifie exactement ce qu'il veut**.

### Avantages sur REST

| REST | GraphQL |
|------|---------|
| Multiple endpoints | Un seul endpoint |
| Over-fetching | Données précises |
| Under-fetching | Tout en une requête |
| Versioning | Évolution continue |

---

## Queries (Lecture)

### 1. Liste simple
```graphql
query {
  praticiens {
    id
    nom
    prenom
    telephone
    ville
  }
}
```

### 2. Avec relation
```graphql
query {
  praticiens {
    id
    nom
    specialite {
      libelle
    }
  }
}
```

### 3. Avec filtre
```graphql
query {
  praticiens(filter: { ville: { _eq: "Paris" } }) {
    id
    nom
    prenom
  }
}
```

### 4. Relations profondes
```graphql
query {
  praticiens {
    nom
    specialite {
      libelle
    }
    structure {
      nom
      ville
    }
  }
}
```

### 5. Filtres multiples
```graphql
query {
  praticiens(filter: {
    ville: { _eq: "Paris" },
    email: { _contains: ".fr" }
  }) {
    nom
    email
  }
}
```

### 6. Filtrage sur relation
```graphql
query {
  praticiens(filter: {
    structure: { ville: { _eq: "Paris" } }
  }) {
    nom
    structure {
      nom
    }
  }
}
```

### 7. Alias pour requêtes multiples
```graphql
query {
  praticiensParis: praticiens(filter: { ville: { _eq: "Paris" } }) {
    nom
    prenom
  }
  praticiensBourdon: praticiens(filter: { ville: { _eq: "Bourdon-les-Bains" } }) {
    nom
    prenom
  }
}
```

### 8. Fragments (réutilisation de champs)
```graphql
fragment PraticienFields on praticiens {
  id
  nom
  prenom
  specialite {
    libelle
  }
}

query {
  praticiensParis: praticiens(filter: { ville: { _eq: "Paris" } }) {
    ...PraticienFields
  }
  praticiensBourdon: praticiens(filter: { ville: { _eq: "Bourdon-les-Bains" } }) {
    ...PraticienFields
  }
}
```

### 9. Variables
```graphql
query GetPraticiensByVille($ville: String!) {
  praticiens(filter: { ville: { _eq: $ville } }) {
    nom
    prenom
  }
}
```

**Variables JSON:**
```json
{ "ville": "Paris" }
```

### 10. Liste avec sous-collections
```graphql
query {
  structures {
    nom
    ville
    praticiens {
      nom
      prenom
      email
      specialite {
        libelle
      }
    }
  }
}
```

---

## Mutations (Écriture)

### 1. Créer (INSERT)
```graphql
mutation {
  create_specialites_item(data: {
    libelle: "cardiologie"
    description: "Maladies du coeur"
  }) {
    id
    libelle
  }
}
```

### 2. Créer avec relation existante
```graphql
mutation {
  create_praticiens_item(data: {
    nom: "Martin"
    prenom: "Pierre"
    ville: "Lyon"
    email: "pierre.martin@example.com"
    telephone: "04 72 00 00 00"
    specialite: { id: 6 }
  }) {
    id
    nom
    specialite {
      libelle
    }
  }
}
```

### 3. Modifier (UPDATE)
```graphql
mutation {
  update_praticiens_item(
    id: "UUID_PRATICIEN"
    data: {
      specialite: { id: 6 }
    }
  ) {
    id
    nom
    specialite {
      libelle
    }
  }
}
```

### 4. Créer avec création imbriquée
```graphql
mutation {
  create_praticiens_item(data: {
    nom: "Durand"
    prenom: "Michel"
    ville: "Bordeaux"
    email: "michel.durand@example.com"
    telephone: "05 56 00 00 00"
    specialite: {
      create: {
        libelle: "chirurgie"
        description: "Interventions chirurgicales"
      }
    }
  }) {
    id
    specialite {
      id
      libelle
    }
  }
}
```

### 5. Supprimer (DELETE)
```graphql
mutation {
  delete_praticiens_item(id: "UUID") {
    id
  }
}

# Ou plusieurs à la fois
mutation {
  delete_praticiens_items(ids: ["UUID1", "UUID2"]) {
    ids
  }
}
```

---

## Authentification

### Token statique
```bash
curl -X POST "http://localhost:8055/graphql" \
  -H "Authorization: Bearer VOTRE_TOKEN_STATIQUE" \
  -H "Content-Type: application/json" \
  -d '{"query": "query { praticiens { nom } }"}'
```

### JWT
```bash
# 1. Obtenir le token
curl -X POST "http://localhost:8055/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "password"}'

# 2. Utiliser le token
curl -X POST "http://localhost:8055/graphql" \
  -H "Authorization: Bearer JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"query": "query { moyens_paiement { libelle } }"}'
```

---

# Résumé Comparatif

| Aspect | Doctrine | MongoDB | REST API | GraphQL |
|--------|----------|---------|----------|---------|
| **Type** | ORM | NoSQL | API REST | Query Language |
| **Langage** | PHP/DQL | JavaScript | HTTP | GraphQL |
| **Données** | Objets PHP | Documents JSON | JSON | JSON |
| **Relations** | Annotations | Embedded/Refs | ?fields= | {} imbriqués |
| **Filtrage** | WHERE/DQL | find({}) | ?filter[]= | filter: {} |
| **Création** | persist+flush | insertOne | POST | mutation create |
| **Modification** | flush | updateOne | PATCH | mutation update |
| **Suppression** | remove+flush | deleteOne | DELETE | mutation delete |

---

# Conseils pour avoir 20/20

1. **Comprendre les concepts** : Ne pas juste copier le code, comprendre pourquoi
2. **Tester chaque requête** : Vérifier les résultats attendus
3. **Commenter son code** : Expliquer ce que fait chaque partie
4. **Gérer les erreurs** : Ajouter des vérifications (if, try/catch)
5. **Organiser le code** : Structure claire, nommage cohérent

---

*Document généré pour les TPs de Nouveaux Paradigmes de Bases de Données - IUT Nancy Charlemagne*
