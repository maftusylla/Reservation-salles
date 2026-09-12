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
$connexion = $capsule->getConnection();

if (!$schema->hasTable('migrations')) {
    $schema->create('migrations', function ($table) {
        $table->increments('id');
        $table->string('migration');
        $table->timestamp('appliquee_le')->useCurrent();
    });
    echo "Table 'migrations' créée." . PHP_EOL;
}

$dejaAppliquees = $connexion->table('migrations')->pluck('migration')->all();

$fichiersMigrations = glob(__DIR__ . '/migrations/*.php');
sort($fichiersMigrations);

foreach ($fichiersMigrations as $fichier) {
    $nom = basename($fichier, '.php');

    if (in_array($nom, $dejaAppliquees, true)) {
        echo "Migration '{$nom}' déjà appliquée, ignorée." . PHP_EOL;
        continue;
    }

    /** @var MigrationInterface $migration */
    $migration = require $fichier;

    $migration->up($schema);
    $connexion->table('migrations')->insert(['migration' => $nom]);

    echo "Migration '{$nom}' appliquée." . PHP_EOL;
}