<?php
// database/migrations/2024_03_26_000009_create_actions_utilisateurs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actionsUtilisateurs', function (Blueprint $table) {
            $table->id('idActionsUtilisateurs');
            $table->foreignId('idUtilisateur')
                ->constrained('utilisateurs', 'id')
                ->onDelete('cascade');
            $table->enum('typeAction', ['Consultation', 'Modification', 'Ajout', 'Suppression']);
            $table->enum('entiteCible', ['Objet', 'Service', 'Utilisateur', 'Événement']);
            $table->integer('idCible');
            $table->dateTime('dateAction')->useCurrent();
            $table->float('pointsGagne')->default(0.5);
        });
    }

    public function down()
    {
        Schema::dropIfExists('actionsUtilisateurs');
    }
};