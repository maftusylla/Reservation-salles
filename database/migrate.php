<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Migration\MigrationInterface;

$demarrerEloquent = require dirname(__DIR__) . '/config/eloquent.php';

try {
    $capsule = $demarrerEloquent();
    $capsule->getConnection()->getPdo();

    $config = require dirname(__DIR__) . '/config/database.php';
    echo "Connexion réussie à la base '{$config['database']}'." . PHP_EOL;
} catch (\Throwable $e) {
    fwrite(STDERR, 'Erreur de connexion : ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$schema = $capsule->schema();

$fichiersMigrations = glob(__DIR__ . '/migrations/*.php');
sort($fichiersMigrations);

foreach ($fichiersMigrations as $fichier) {
    /** @var MigrationInterface $migration */
    $migration = require $fichier;

    $nom = basename($fichier, '.php');
    $migration->up($schema);
    echo "Migration '{$nom}' appliquée." . PHP_EOL;
}