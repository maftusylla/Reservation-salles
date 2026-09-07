<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$config = require dirname(__DIR__, 2) . '/config/database.php';
try {
    $capsule = new Capsule();
    $capsule->addConnection($config);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $capsule->getConnection()->getPdo();
    echo "Connexion réussie à la base '{$config['database']}'." . PHP_EOL;
} catch (\Throwable $e) {
    fwrite(STDERR, 'Erreur de connexion : ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$schema = $capsule->schema();

if (! $schema->hasTable('salles')) {
    $schema->create('salles', function (Blueprint $table) {
        $table->id();
        $table->string('nom', 100);
        $table->string('batiment', 100);
        $table->unsignedInteger('capacite');
        $table->enum('type', ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion']);
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
    echo "Table 'salles' créée." . PHP_EOL;
} else {
    echo "Table 'salles' déjà existante." . PHP_EOL;
}

if (! $schema->hasTable('reservations')) {
    $schema->create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('salle_id')->constrained('salles');
        $table->string('responsable', 120);
        $table->string('email');
        $table->string('motif', 255);
        $table->dateTime('date_debut');
        $table->dateTime('date_fin');
        $table->enum('statut', ['confirmée', 'annulée'])->default('confirmée');
        $table->timestamps();
    });
    echo "Table 'reservations' créée." . PHP_EOL;
} else {
    echo "Table 'reservations' déjà existante." . PHP_EOL;
}