<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('niveauxCompetences', function (Blueprint $table) {
            $table->id('idNiveauxCompetences');
            $table->enum('niveau', ['Débutant', 'Intermédiaire', 'Avancé', 'Expert']);
            $table->float('pointsRequis');
            $table->text('descriptionNiveauxCompetences');
        });
    }

    public function down()
    {
        Schema::dropIfExists('niveauxCompetences');
    }
};