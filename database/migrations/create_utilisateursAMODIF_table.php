<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('pseudo')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Ajout
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->date('dateNaissance')->nullable();
            $table->integer('age')->nullable();
            $table->enum('sexe', ['Homme', 'Femme', 'Autre'])->nullable();
            $table->enum('typeMembre', ['Spectateur', 'Athlète', 'Entraîneur', 'Personnel technique', 'Sécurité', 'Administratif']);
            $table->enum('niveau', ['Débutant', 'Intermédiaire', 'Avancé', 'Expert'])->default('Débutant');
            $table->float('points')->default(0);
            $table->string('photo')->nullable();
            $table->datetime('dateInscription')->useCurrent();
            $table->datetime('derniereConnexion')->nullable();
            $table->boolean('estActif')->default(true);
            $table->boolean('estVerifie')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
};