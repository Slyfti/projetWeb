<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('objetsConnectes', function (Blueprint $table) {
            $table->id('idObjetsConnectes');
            $table->string('nom');
            $table->foreignId('idCategorie')->constrained('categoriesObjets', 'idCategoriesObjets');
            $table->text('descriptionObjetsConnectes');
            $table->enum('etat', ['Actif', 'Inactif', 'Maintenance'])->default('Actif');
            $table->enum('mode', ['Automatique', 'Manuel'])->default('Automatique');
            $table->string('connectivite');
            $table->float('niveauBatterie');
            $table->datetime('derniereInteraction');
            $table->float('puissance');
            $table->float('consommationActuelle');
            $table->float('dureeVieEstimee');
            $table->date('dateInstallation');
            $table->date('derniereMaintenance');
            $table->foreignId('idZone')->constrained('zonesStade', 'idZonesStade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('objetsConnectes');
    }
};