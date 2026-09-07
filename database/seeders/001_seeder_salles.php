<?php

declare(strict_types=1);

use App\Model\Salle;
use App\Seeder\SeederInterface;

return new class implements SeederInterface {
    public function run(): void
    {
        $salles = [
            ['nom' => 'Amphithéâtre A', 'batiment' => 'Bâtiment principal', 'capacite' => 250, 'type' => 'amphitheatre', 'active' => true],
            ['nom' => 'Salle B12', 'batiment' => 'Bâtiment B', 'capacite' => 40, 'type' => 'cours', 'active' => true],
            ['nom' => 'Laboratoire Chimie', 'batiment' => 'Bâtiment C', 'capacite' => 24, 'type' => 'laboratoire', 'active' => true],
            ['nom' => 'Salle Informatique 1', 'batiment' => 'Bâtiment C', 'capacite' => 30, 'type' => 'informatique', 'active' => true],
            ['nom' => 'Salle de réunion', 'batiment' => 'Bâtiment principal', 'capacite' => 12, 'type' => 'reunion', 'active' => true],
        ];

        foreach ($salles as $donneesSalle) {
            Salle::firstOrCreate(['nom' => $donneesSalle['nom']], $donneesSalle);
        }

        echo 'Seeder salles : ' . count($salles) . ' salles vérifiées/créées.' . PHP_EOL;
    }
};