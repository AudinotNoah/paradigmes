# TP4 - GraphQL avec Directus

## Endpoint GraphQL : http://localhost:8055/graphql

## Outil recommandé : Bruno, Postman ou Insomnia

---

# PARTIE 1 : Requêtes Query

---

## 1. Liste des praticiens (id, nom, prénom, téléphone, ville)

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

---

## 2. Idem avec le libellé de la spécialité

```graphql
query {
  praticiens {
    id
    nom
    prenom
    telephone
    ville
    specialite {
      libelle
    }
  }
}
```

---

## 3. Avec filtre sur ville = "Paris"

```graphql
query {
  praticiens(filter: { ville: { _eq: "Paris" } }) {
    id
    nom
    prenom
    telephone
    ville
    specialite {
      libelle
    }
  }
}
```

---

## 4. Avec nom et ville de la structure d'appartenance

```graphql
query {
  praticiens(filter: { ville: { _eq: "Paris" } }) {
    id
    nom
    prenom
    telephone
    ville
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

---

## 5. Avec filtre sur emails contenant ".fr"

```graphql
query {
  praticiens(filter: {
    ville: { _eq: "Paris" },
    email: { _contains: ".fr" }
  }) {
    id
    nom
    prenom
    telephone
    ville
    email
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

---

## 6. Praticiens rattachés à une structure dont la ville est "Paris"

```graphql
query {
  praticiens(filter: {
    structure: { ville: { _eq: "Paris" } }
  }) {
    id
    nom
    prenom
    telephone
    ville
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

---

## 7. Deux listes avec alias (Paris et Bourdon-les-Bains)

```graphql
query {
  praticiensParis: praticiens(filter: { ville: { _eq: "Paris" } }) {
    id
    nom
    prenom
    telephone
    ville
    specialite {
      libelle
    }
  }
  praticiensBourdon: praticiens(filter: { ville: { _eq: "Bourdon-les-Bains" } }) {
    id
    nom
    prenom
    telephone
    ville
    specialite {
      libelle
    }
  }
}
```

---

## 8. Avec fragment pour les champs du résultat

```graphql
fragment PraticienFields on praticiens {
  id
  nom
  prenom
  telephone
  ville
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

---

## 9. Avec variable pour paramétrer la ville

```graphql
query GetPraticiensByVille($ville: String!) {
  praticiens(filter: { ville: { _eq: $ville } }) {
    id
    nom
    prenom
    telephone
    ville
    specialite {
      libelle
    }
  }
}
```

**Variables:**
```json
{
  "ville": "Paris"
}
```

---

## 10. Liste des structures avec praticiens

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

# PARTIE 2 : Autorisations dans Directus

## Configuration des rôles et policies

### Étape 1 : Créer un rôle "API Reader"
1. Aller dans Settings > Roles & Permissions
2. Créer un nouveau rôle "API Reader"
3. Donner les droits de lecture sur toutes les collections

### Étape 2 : Créer les utilisateurs
1. Créer un utilisateur avec token statique :
   - Email: api-static@example.com
   - Token: générer un token statique dans les paramètres de l'utilisateur

2. Créer un utilisateur avec JWT :
   - Email: api-jwt@example.com
   - Mot de passe: password123

### Étape 3 : Retirer les droits du rôle Public
1. Aller dans Settings > Roles & Permissions > Public
2. Retirer les droits de lecture sur : motifs_visite, moyens_paiement

---

## Requêtes avec authentification

### 1. Lister les moyens de paiement

**Sans authentification (devrait échouer) :**
```graphql
query {
  moyens_paiement {
    id
    libelle
  }
}
```

**Avec token statique :**
```bash
curl -X POST "http://localhost:8055/graphql" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer VOTRE_TOKEN_STATIQUE" \
  -d '{"query": "query { moyens_paiement { id libelle } }"}'
```

### 2. Spécialités avec motifs de visite associés

**Avec JWT :**

D'abord obtenir le token JWT :
```bash
curl -X POST "http://localhost:8055/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email": "api-jwt@example.com", "password": "password123"}'
```

Puis utiliser le token :
```graphql
query {
  specialites {
    id
    libelle
    motifs_visite {
      id
      libelle
    }
  }
}
```

```bash
curl -X POST "http://localhost:8055/graphql" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer VOTRE_JWT_TOKEN" \
  -d '{"query": "query { specialites { id libelle motifs_visite { id libelle } } }"}'
```

---

# PARTIE 3 : Mutations GraphQL

---

## 1. Créer la spécialité "cardiologie"

```graphql
mutation {
  create_specialites_item(data: {
    libelle: "cardiologie"
    description: "Maladies du coeur et du système cardiovasculaire"
  }) {
    id
    libelle
  }
}
```

---

## 2. Créer un praticien (nom, prénom, ville, email, téléphone)

```graphql
mutation {
  create_praticiens_item(data: {
    nom: "Martin"
    prenom: "Pierre"
    ville: "Lyon"
    email: "pierre.martin@example.com"
    telephone: "04 72 00 00 00"
  }) {
    id
    nom
    prenom
  }
}
```

---

## 3. Modifier le praticien pour le rattacher à "cardiologie"

```graphql
mutation {
  update_praticiens_item(
    id: "ID_DU_PRATICIEN_CREE"
    data: {
      specialite: { id: ID_CARDIOLOGIE }
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

---

## 4. Créer un praticien en le rattachant à "cardiologie"

```graphql
mutation {
  create_praticiens_item(data: {
    nom: "Bernard"
    prenom: "Sophie"
    ville: "Marseille"
    email: "sophie.bernard@example.com"
    telephone: "04 91 00 00 00"
    specialite: { id: ID_CARDIOLOGIE }
  }) {
    id
    nom
    prenom
    specialite {
      libelle
    }
  }
}
```

---

## 5. Créer un praticien et créer sa spécialité "chirurgie" en même temps

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
    nom
    prenom
    specialite {
      id
      libelle
    }
  }
}
```

---

## 6. Ajouter un praticien à la spécialité "chirurgie"

```graphql
mutation {
  create_praticiens_item(data: {
    nom: "Leroy"
    prenom: "Anne"
    ville: "Toulouse"
    email: "anne.leroy@example.com"
    telephone: "05 61 00 00 00"
    specialite: { id: ID_CHIRURGIE }
  }) {
    id
    nom
    prenom
    specialite {
      libelle
    }
  }
}
```

---

## 7. Modifier le premier praticien pour le rattacher à une structure existante

```graphql
mutation {
  update_praticiens_item(
    id: "ID_PREMIER_PRATICIEN"
    data: {
      structure: { id: "3444bdd2-8783-3aed-9a5e-4d298d2a2d7c" }
    }
  ) {
    id
    nom
    structure {
      nom
      ville
    }
  }
}
```

---

## 8. Supprimer les deux derniers praticiens créés

```graphql
mutation {
  delete_praticiens_items(ids: [
    "ID_AVANT_DERNIER_PRATICIEN",
    "ID_DERNIER_PRATICIEN"
  ]) {
    ids
  }
}
```

Ou un par un :

```graphql
mutation {
  delete_praticiens_item(id: "ID_PRATICIEN") {
    id
  }
}
```

---

# Résumé des opérations GraphQL

| Opération | Syntaxe Directus |
|-----------|------------------|
| Lire tous | `collection_name { fields }` |
| Lire un | `collection_name_by_id(id: "...") { fields }` |
| Créer | `create_collection_item(data: {...}) { fields }` |
| Modifier | `update_collection_item(id: "...", data: {...}) { fields }` |
| Supprimer | `delete_collection_item(id: "...") { id }` |
| Supprimer plusieurs | `delete_collection_items(ids: [...]) { ids }` |
