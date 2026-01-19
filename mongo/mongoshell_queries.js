/**
 * TP2 - MongoDB
 * Section 1 : Requêtes simples sur MongoShell
 *
 * Pour exécuter ces requêtes :
 * 1. Entrer dans le conteneur mongo : docker exec -it <mongo_container> mongosh
 * 2. Sélectionner la base : use chopizza
 * 3. Exécuter les requêtes ci-dessous
 */

// Utiliser la base chopizza
use chopizza

// ============================================================================
// 1.1 Liste des produits
// ============================================================================
db.produits.find()

// ============================================================================
// 1.2 Compter les produits
// ============================================================================
db.produits.countDocuments()

// ============================================================================
// 1.3 Lister les produits triés par numero décroissant
// ============================================================================
db.produits.find().sort({ numero: -1 })

// ============================================================================
// 1.4 Le produit de libellé "Margherita"
// ============================================================================
db.produits.findOne({ libelle: "Margherita" })

// ============================================================================
// 1.5 Produits de la catégorie "Boissons"
// ============================================================================
db.produits.find({ categorie: "Boissons" })

// ============================================================================
// 1.6 Liste des produits : afficher categorie, numero, libelle
// ============================================================================
db.produits.find({}, { categorie: 1, numero: 1, libelle: 1, _id: 0 })

// ============================================================================
// 1.7 Idem avec en plus la taille et le tarif
// ============================================================================
db.produits.find({}, { categorie: 1, numero: 1, libelle: 1, tarifs: 1, _id: 0 })

// ============================================================================
// 1.8 Produits avec un tarif < 8.0
// ============================================================================
db.produits.find({ "tarifs.tarif": { $lt: 8.0 } })

// ============================================================================
// 1.9 Produits avec un tarif grande taille < 8.0
// ============================================================================
db.produits.find({
    tarifs: {
        $elemMatch: {
            taille: "grande",
            tarif: { $lt: 8.0 }
        }
    }
})

// ============================================================================
// 1.10 Insérer un nouveau produit
// ============================================================================
db.produits.insertOne({
    numero: 11,
    libelle: "Calzone",
    description: "Pizza fermée garnie de jambon, mozzarella et champignons",
    image: "https://example.com/calzone.png",
    categorie: "Pizzas",
    tarifs: [
        { taille: "normale", tarif: 10.99 },
        { taille: "grande", tarif: 13.99 }
    ],
    recettes: []
})

// ============================================================================
// 1.11 Les recettes associées au produit 1
// ============================================================================
// D'abord récupérer le produit 1
var produit1 = db.produits.findOne({ numero: 1 })

// Ensuite afficher les recettes liées
db.recettes.find({
    _id: { $in: produit1.recettes }
})

// Ou en une seule requête avec aggregation :
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
