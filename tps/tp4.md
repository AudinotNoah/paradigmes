IUT Nancy Charlemagne
Département d'Informatique
BUT 3 Informatique - DWM

# Nouveaux Paradigmes de Bases de Données

## TD 4 : requêtes GraphQL

### Préparation

On utilise l’api GraphQL proposée par Directus, avec les collections et données créées dans le TD4.
Utiliser un client d’api capable d’adresser des requêtes GraphQL : Bruno, postman, insomnia

### Requêtes Query

1. liste des praticiens en précisant : id, nom, prénom, téléphone, ville
2. Idem, en ajoutant le libellé de la spécialité du praticien
3. Idem, en ajoutant un filtre pour sélectionner les praticiens dont la ville est égale à «Paris» (ou une valeur de votre jeu de données)
4. Idem, en ajoutant le nom et la ville de la structure d’appartenance du praticien,
5. Idem, en ajoutant un filtre pour retenir les emails contenant ".fr"
6. lister les praticiens rattachés à une structure dont la ville est "Paris".
7. Requête retournant une liste de praticiens installés à Paris et une liste de praticiens installés à Bourdon-les-Bains ; utiliser des alias.
8. Transformer la requête précédente de façon à utiliser un fragment correspondant aux champs du résultat.
9. Transformer la requête 3 pour utiliser une variable de façon à paramétrer la requête par le nom de la ville souhaitée.
10. Liste des structures, en indiquant leur nom et ville, en ajoutant la liste des praticiens attachés à chaque structure en indiquant leur nom, prénom, email ainsi que le libellé de leur spécialité.

### Autorisations dans Directus

Créer un rôle et une policy pour accéder aux données au travers de l’api. Donner les droits de lecture sur l’ensemble des collections.
Créer deux utilisateurs appartenant à ce rôle :

* un utilisateur utilisant un token statique
* un utilisateur utilisant un token JWT

Retirer les droits de lecture au rôle ‘Public’ sur les collections motifs_visite et moyens_paiement.
Exécuter les requêtes ci-dessous. Vérifier qu’elles ne fonctionnent plus sans authentification, puis utiliser une authentification : avec un utilisateur pour une requête, avec l’autre utilisateur pour l’autre requête.

1. Lister les moyens de paiement
2. lister les spécialités en indiquant les motifs de visite associés à chacune

# Mutations GraphQL

Ecrire les mutations de la liste suivante. Penser à donner les droits nécessaires au rôle créé dans la question précédente.

1. Créer la spécialité «cardiologie»
2. créer un praticien: nom, prénom, ville, email, téléphone
3. modifier le praticien pour le rattacher à la spécialité «cardiologie»
4. créer un praticien en le rattachant à la spécialité «cardiologie»
5. créer un praticien et créer en même temps sa spécialité «chirurgie»
6. ajouter un praticien à la spécialité «chirurgie»
7. modifier le premier praticien créé pour le rattacher à une structure existante
8. supprimer les deux dernier praticiens créés.
