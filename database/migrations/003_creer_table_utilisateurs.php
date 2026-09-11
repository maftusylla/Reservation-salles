<?php

declare(strict_types=1);

use App\Migration\MigrationInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return new class implements MigrationInterface {
    public function up(Builder $schema): void
    {
        if ($schema->hasTable('utilisateurs')) {
            return;
        }

        $schema->create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 120);
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->enum('role', ['responsable', 'admin'])->default('responsable');
            $table->timestamps();
        });
    }

    public function down(Builder $schema): void
    {
        $schema->dropIfExists('utilisateurs');
    }
};