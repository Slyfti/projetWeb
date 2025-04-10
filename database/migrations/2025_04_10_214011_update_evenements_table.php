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
        Schema::table('evenements', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà avant de les ajouter
            if (!Schema::hasColumn('evenements', 'meteo')) {
                $table->string('meteo', 100)->nullable();
            }
            if (!Schema::hasColumn('evenements', 'consignes_securite')) {
                $table->text('consignes_securite')->nullable();
            }
            if (!Schema::hasColumn('evenements', 'activites_autour')) {
                $table->text('activites_autour')->nullable();
            }
            if (!Schema::hasColumn('evenements', 'logo_equipe_domicile')) {
                $table->string('logo_equipe_domicile')->nullable();
            }
            if (!Schema::hasColumn('evenements', 'logo_equipe_exterieur')) {
                $table->string('logo_equipe_exterieur')->nullable();
            }
            if (!Schema::hasColumn('evenements', 'resultat')) {
                $table->string('resultat', 50)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropColumn([
                'meteo',
                'consignes_securite',
                'activites_autour',
                'logo_equipe_domicile',
                'logo_equipe_exterieur',
                'resultat'
            ]);
        });
    }
};
