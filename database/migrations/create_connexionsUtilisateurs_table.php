<?php
// database/migrations/2024_03_26_000008_create_connexions_utilisateurs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('connexionsUtilisateurs', function (Blueprint $table) {
            $table->id('idConnexionsUtilisateurs');
            $table->foreignId('idUtilisateur')
                ->constrained('utilisateurs', 'idUtilisateurs')
                ->onDelete('cascade');
            $table->dateTime('dateConnexion')->useCurrent();
            $table->float('pointsGagne')->default(0.25);
        });
    }

    public function down()
    {
        Schema::dropIfExists('connexionsUtilisateurs');
    }
};