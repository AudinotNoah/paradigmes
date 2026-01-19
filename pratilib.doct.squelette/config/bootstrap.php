<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration Doctrine avec attributs PHP 8
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity'],
    isDevMode: true,
);

// Connexion à la base de données PostgreSQL
$connectionParams = [
    'driver' => 'pdo_pgsql',
    'host' => 'praticien.db',
    'port' => 5432,
    'dbname' => 'prati',
    'user' => 'prati',
    'password' => 'prati',
];

$connection = DriverManager::getConnection($connectionParams, $config);
$entityManager = new EntityManager($connection, $config);

return $entityManager;
