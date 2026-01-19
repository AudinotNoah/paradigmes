<?php
/**
 * TP2 - MongoDB
 * Toutes les réponses aux exercices
 */

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

require_once __DIR__ . "/../src/vendor/autoload.php";

$client = new Client("mongodb://mongo");
$db = $client->chopizza;

echo "<h1>TP2 - MongoDB - Réponses aux exercices</h1>";

// ============================================================================
// SECTION 2 : Requêtes en PHP
// ============================================================================
echo "<h2>Section 2 : Requêtes en PHP</h2>";

// ----------------------------------------------------------------------------
// 2.1 Liste des produits : numero, categorie, libelle
// ----------------------------------------------------------------------------
echo "<h3>2.1 - Liste des produits (numero, categorie, libelle)</h3>";
$produits = $db->produits->find([], [
    'projection' => ['numero' => 1, 'categorie' => 1, 'libelle' => 1]
]);

echo "<table border='1'><tr><th>Numero</th><th>Catégorie</th><th>Libellé</th></tr>";
foreach ($produits as $produit) {
    echo "<tr>";
    echo "<td>" . $produit['numero'] . "</td>";
    echo "<td>" . $produit['categorie'] . "</td>";
    echo "<td>" . $produit['libelle'] . "</td>";
    echo "</tr>";
}
echo "</table><br>";

// ----------------------------------------------------------------------------
// 2.2 Produit numéro 6 : libellé, catégorie, description, tarifs
// ----------------------------------------------------------------------------
echo "<h3>2.2 - Produit numéro 6 (détails complets)</h3>";
$produit6 = $db->produits->findOne(['numero' => 6]);

if ($produit6) {
    echo "<pre>";
    echo "Libellé: " . $produit6['libelle'] . "\n";
    echo "Catégorie: " . $produit6['categorie'] . "\n";
    echo "Description: " . $produit6['description'] . "\n";
    echo "Tarifs:\n";
    foreach ($produit6['tarifs'] as $tarif) {
        echo "  - Taille " . $tarif['taille'] . ": " . $tarif['tarif'] . " €\n";
    }
    echo "</pre>";
}

// ----------------------------------------------------------------------------
// 2.3 Produits avec tarif taille normale <= 3.0
// ----------------------------------------------------------------------------
echo "<h3>2.3 - Produits avec tarif taille normale <= 3.0</h3>";
$produitsNormale = $db->produits->find([
    'tarifs' => [
        '$elemMatch' => [
            'taille' => 'normale',
            'tarif' => ['$lte' => 3.0]
        ]
    ]
]);

echo "<ul>";
foreach ($produitsNormale as $p) {
    $tarifNormale = 0;
    foreach ($p['tarifs'] as $t) {
        if ($t['taille'] === 'normale') {
            $tarifNormale = $t['tarif'];
            break;
        }
    }
    echo "<li>" . $p['libelle'] . " - Tarif normale: " . $tarifNormale . " €</li>";
}
echo "</ul>";

// ----------------------------------------------------------------------------
// 2.4 Produits associés à exactement 4 recettes
// ----------------------------------------------------------------------------
echo "<h3>2.4 - Produits associés à 4 recettes</h3>";
$produits4Recettes = $db->produits->find([
    'recettes' => ['$size' => 4]
]);

echo "<ul>";
foreach ($produits4Recettes as $p) {
    echo "<li>" . $p['libelle'] . " (catégorie: " . $p['categorie'] . ") - " . count($p['recettes']) . " recettes</li>";
}
echo "</ul>";

// ----------------------------------------------------------------------------
// 2.5 Produit n°6 avec recettes associées (nom et difficulté)
// ----------------------------------------------------------------------------
echo "<h3>2.5 - Produit n°6 avec recettes</h3>";
$produit6 = $db->produits->findOne(['numero' => 6]);

if ($produit6) {
    echo "<pre>";
    echo "Produit: " . $produit6['libelle'] . "\n";
    echo "Description: " . $produit6['description'] . "\n";
    echo "\nRecettes associées:\n";

    foreach ($produit6['recettes'] as $recetteRef) {
        $recette = $db->recettes->findOne(['_id' => $recetteRef]);
        if ($recette) {
            echo "  - " . $recette['nom'] . " (Difficulté: " . $recette['difficulte'] . ")\n";
        }
    }
    echo "</pre>";
}

// ----------------------------------------------------------------------------
// 2.6 Fonction retournant les données descriptives d'un produit
// ----------------------------------------------------------------------------
echo "<h3>2.6 - Fonction getProductInfo(numero, taille)</h3>";

function getProductInfo(MongoDB\Database $db, int $numero, string $taille): ?array
{
    $produit = $db->produits->findOne(['numero' => $numero]);

    if (!$produit) {
        return null;
    }

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

// Test de la fonction
$info = getProductInfo($db, 1, 'grande');
echo "<pre>";
echo "Résultat de getProductInfo(1, 'grande'):\n";
echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo "</pre>";

$info2 = getProductInfo($db, 5, 'normale');
echo "<pre>";
echo "Résultat de getProductInfo(5, 'normale'):\n";
echo json_encode($info2, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo "</pre>";

// ============================================================================
// SECTION 3 : Application (navigation catalogue)
// ============================================================================
echo "<h2>Section 3 : Application - Navigation par catégorie</h2>";

// Récupérer les catégories distinctes
$categories = $db->produits->distinct('categorie');

// Afficher le formulaire de sélection de catégorie
echo "<form method='GET'>";
echo "<label>Sélectionner une catégorie: </label>";
echo "<select name='categorie'>";
echo "<option value=''>-- Toutes --</option>";
foreach ($categories as $cat) {
    $selected = (isset($_GET['categorie']) && $_GET['categorie'] === $cat) ? 'selected' : '';
    echo "<option value='$cat' $selected>$cat</option>";
}
echo "</select>";
echo " <button type='submit'>Afficher</button>";
echo "</form><br>";

// Afficher les produits de la catégorie sélectionnée
$filter = [];
if (!empty($_GET['categorie'])) {
    $filter = ['categorie' => $_GET['categorie']];
}

$produitsCat = $db->produits->find($filter);

echo "<table border='1'>";
echo "<tr><th>Numéro</th><th>Libellé</th><th>Description</th><th>Tarifs</th></tr>";
foreach ($produitsCat as $p) {
    echo "<tr>";
    echo "<td>" . $p['numero'] . "</td>";
    echo "<td>" . $p['libelle'] . "</td>";
    echo "<td>" . $p['description'] . "</td>";
    echo "<td>";
    foreach ($p['tarifs'] as $t) {
        echo ucfirst($t['taille']) . ": " . $t['tarif'] . " €<br>";
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

// ============================================================================
// Formulaire d'ajout de produit
// ============================================================================
echo "<h3>Formulaire d'ajout de produit</h3>";

// Récupérer les tailles distinctes
$tailles = ['normale', 'grande'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $newProduct = [
        'numero' => (int)$_POST['numero'],
        'libelle' => $_POST['libelle'],
        'description' => $_POST['description'],
        'image' => $_POST['image'] ?? '',
        'categorie' => $_POST['categorie_new'],
        'tarifs' => [
            ['taille' => 'normale', 'tarif' => (float)$_POST['tarif_normale']],
            ['taille' => 'grande', 'tarif' => (float)$_POST['tarif_grande']]
        ],
        'recettes' => []
    ];

    $result = $db->produits->insertOne($newProduct);
    echo "<p style='color: green;'>Produit ajouté avec succès! ID: " . $result->getInsertedId() . "</p>";
}

echo "<form method='POST'>";
echo "<table>";
echo "<tr><td>Numéro:</td><td><input type='number' name='numero' required></td></tr>";
echo "<tr><td>Libellé:</td><td><input type='text' name='libelle' required></td></tr>";
echo "<tr><td>Description:</td><td><textarea name='description' required></textarea></td></tr>";
echo "<tr><td>Image URL:</td><td><input type='text' name='image'></td></tr>";
echo "<tr><td>Catégorie:</td><td><select name='categorie_new'>";
foreach ($categories as $cat) {
    echo "<option value='$cat'>$cat</option>";
}
echo "</select></td></tr>";
echo "<tr><td>Tarif normale (€):</td><td><input type='number' step='0.01' name='tarif_normale' required></td></tr>";
echo "<tr><td>Tarif grande (€):</td><td><input type='number' step='0.01' name='tarif_grande' required></td></tr>";
echo "</table>";
echo "<button type='submit' name='add_product'>Ajouter le produit</button>";
echo "</form>";

echo "<hr><p><strong>Fin des exercices TP2 (PHP)</strong></p>";
