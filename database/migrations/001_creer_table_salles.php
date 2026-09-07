<?php

declare(strict_types=1);

use App\Migration\MigrationInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return new class implements MigrationInterface {
    public function up(Builder $schema): void
    {
        if ($schema->hasTable('salles')) {
            return;
        }

        $schema->create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->enum('type', ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion']);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(Builder $schema): void
    {
        $schema->dropIfExists('salles');
    }
};