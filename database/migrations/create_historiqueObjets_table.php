<?php
// database/migrations/2024_03_26_000010_create_historique_objets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historiqueObjets', function (Blueprint $table) {
            $table->id('idHistoriqueObjets');
            $table->foreignId('idObjetsConnectes')
                ->constrained('objetsConnectes', 'idObjetsConnectes')
                ->onDelete('cascade');
            $table->dateTime('dateModification')->useCurrent();
            $table->string('ancienEtat', 50);
            $table->string('nouvelEtat', 50);
            $table->foreignId('idUtilisateur')
                ->constrained('utilisateurs', 'id')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historiqueObjets');
    }
};