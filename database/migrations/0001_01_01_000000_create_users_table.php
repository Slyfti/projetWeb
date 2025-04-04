<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
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

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
