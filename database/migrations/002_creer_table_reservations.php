<?php

declare(strict_types=1);

use App\Migration\MigrationInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return new class implements MigrationInterface {
    public function up(Builder $schema): void
    {
        if ($schema->hasTable('reservations')) {
            return;
        }

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
    }

    public function down(Builder $schema): void
    {
        $schema->dropIfExists('reservations');
    }
};