# TP3 - Directus REST API

## Configuration préalable

1. Démarrer Directus : `docker-compose up -d`
2. Accéder à l'interface admin : http://localhost:8055
3. Se connecter : admin@example.com / d1r3ctu5
4. Créer les collections selon le modèle (specialites, praticiens, structures, motifs_visite, moyens_paiement)
5. Importer les données (CSV avec séparateur ;)

## Requêtes REST API

### Base URL : http://localhost:8055

---

## 1. Liste des praticiens

```http
GET /items/praticiens
```

**Curl:**
```bash
curl -X GET "http://localhost:8055/items/praticiens"
```

---

## 2. La spécialité d'ID 2

```http
GET /items/specialites/2
```

**Curl:**
```bash
curl -X GET "http://localhost:8055/items/specialites/2"
```

---

## 3. La spécialité d'ID 2, avec uniquement son libellé

```http
GET /items/specialites/2?fields=libelle
```

**Curl:**
```bash
curl -X GET "http://localhost:8055/items/specialites/2?fields=libelle"
```

---

## 4. Un praticien avec sa spécialité (libellé)

```http
GET /items/praticiens/8ae1400f-d46d-3b50-b356-269f776be532?fields=*,specialite.libelle
```

**Curl:**
```bash
curl -X GET "http://localhost:8055/items/praticiens/8ae1400f-d46d-3b50-b356-269f776be532?fields=*,specialite.libelle"
```

---

## 5. Une structure (nom, ville) et la liste des praticiens rattachés (nom, prenom)

```http
GET /items/structures/3444bdd2-8783-3aed-9a5e-4d298d2a2d7c?fields=nom,ville,praticiens.nom,praticiens.prenom
```

**Curl:**
```bash
curl -X GET "http://localhost:8055/items/structures/3444bdd2-8783-3aed-9a5e-4d298d2a2d7c?fields=nom,ville,praticiens.nom,praticiens.prenom"
```

---

## 6. Idem en ajoutant le libellé de la spécialité des praticiens

```http
GET /items/structures/3444bdd2-8783-3aed-9a5e-4d298d2a2d7c?fields=nom,ville,praticiens.nom,praticiens.prenom,praticiens.specialite.libelle
```

**Curl:**
```bash
curl -X GET "http://localhost:8055/items/structures/3444bdd2-8783-3aed-9a5e-4d298d2a2d7c?fields=nom,ville,praticiens.nom,praticiens.prenom,praticiens.specialite.libelle"
```

---

## 7. Structures dont le nom de la ville contient "sur" avec praticiens

```http
GET /items/structures?filter[ville][_contains]=sur&fields=nom,ville,praticiens.nom,praticiens.prenom,praticiens.specialite.libelle
```

**Curl:**
```bash
curl -X GET "http://localhost:8055/items/structures?filter[ville][_contains]=sur&fields=nom,ville,praticiens.nom,praticiens.prenom,praticiens.specialite.libelle"
```

---

## Notes importantes

### Opérateurs de filtrage disponibles :
- `_eq` : égal à
- `_neq` : différent de
- `_contains` : contient (texte)
- `_starts_with` : commence par
- `_ends_with` : finit par
- `_gt` : supérieur à
- `_gte` : supérieur ou égal à
- `_lt` : inférieur à
- `_lte` : inférieur ou égal à
- `_in` : dans la liste
- `_nin` : pas dans la liste

### Paramètres utiles :
- `fields` : sélection des champs à retourner
- `filter` : conditions de filtrage
- `sort` : tri des résultats
- `limit` : nombre de résultats max
- `offset` : pagination

### Authentification (si nécessaire) :
```bash
curl -X GET "http://localhost:8055/items/praticiens" \
  -H "Authorization: Bearer VOTRE_TOKEN"
```
