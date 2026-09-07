<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Seeder\SeederInterface;

$demarrerEloquent = require dirname(__DIR__) . '/config/eloquent.php';
$demarrerEloquent();

$fichiersSeeders = glob(__DIR__ . '/seeders/*.php');
sort($fichiersSeeders);

foreach ($fichiersSeeders as $fichier) {
    /** @var SeederInterface $seeder */
    $seeder = require $fichier;

    $nom = basename($fichier, '.php');
    $seeder->run();
    echo "Seeder '{$nom}' exécuté." . PHP_EOL;
}