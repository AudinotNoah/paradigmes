# Nouveaux Paradigmes

AUDINOT Noah, TEMIROV Abdoul-Raouf
DWM-2

---

## Structure du projet

### `pratilib.doct.squelette/`

**TP1 - Doctrine ORM**Implémentation des exercices Doctrine avec entités, repositories et requêtes DQL.

- `public/index.php` : Code principal avec toutes les réponses
- `src/Entity/` : Entités Doctrine (Praticien, Specialite, Structure, etc.)
- `src/Repository/` : Repositories avec méthodes DQL
- `sql/` : Schéma et données PostgreSQL

### `mongo/`

**TP2 - MongoDB**Requêtes MongoDB en mongoshell et application PHP.

- `mongoshell_queries.js` : Requêtes Section 1 (mongoshell)
- `app/public/index.php` : Requêtes PHP Section 2 + Application Section 3
- `data/` : Données JSON (produits, recettes)

### `directus/`

**TP3 & TP4 - Directus REST API & GraphQL**Backend Directus avec requêtes REST et GraphQL.

- `reps.pdf` : Toutes les réponses TP3 (REST) et TP4 (GraphQL)
- `init/` : Scripts SQL pour initialiser les collections et données
- `docker-compose.yml` : Configuration Docker pour Directus---

## Lancement

### TP1 - Doctrine

```bash
cd pratilib.doct.squelette
docker-compose up -d
docker-compose exec prati.app php public/index.php
```

### TP2 - MongoDB

```bash
cd mongo
docker-compose up -d
docker-compose exec php php public/index.php
docker-compose exec mongo mongosh
```

### TP3 & TP4 - Directus

```bash
cd directus
docker-compose up -d
# Accès : http://localhost:8055 (admin@example.com / d1r3ctu5)
```
