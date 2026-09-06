<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\Salle;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = require dirname(__DIR__) . '/config/database.php';

$capsule = new Capsule();
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$salles = [
    ['nom' => 'Amphithéâtre A', 'batiment' => 'Bâtiment principal', 'capacite' => 250, 'type' => 'amphitheatre', 'active' => true],
    ['nom' => 'Salle B12', 'batiment' => 'Bâtiment B', 'capacite' => 40, 'type' => 'cours', 'active' => true],
    ['nom' => 'Laboratoire Chimie', 'batiment' => 'Bâtiment C', 'capacite' => 24, 'type' => 'laboratoire', 'active' => true],
    ['nom' => 'Salle Informatique 1', 'batiment' => 'Bâtiment C', 'capacite' => 30, 'type' => 'informatique', 'active' => true],
    ['nom' => 'Salle de réunion', 'batiment' => 'Bâtiment principal', 'capacite' => 12, 'type' => 'reunion', 'active' => true],
];

foreach ($salles as $donneesSalle) {
    Salle::firstOrCreate(
        ['nom' => $donneesSalle['nom']],
        $donneesSalle
    );
}

echo 'Données initiales ajoutées (' . count($salles) . ' salles vérifiées/créées).' . PHP_EOL;