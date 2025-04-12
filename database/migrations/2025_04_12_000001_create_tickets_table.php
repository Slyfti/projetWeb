<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('idTicket');
            $table->foreignId('idUtilisateur')->constrained('utilisateurs', 'id')->onDelete('cascade');
            $table->foreignId('idEvenements')->constrained('evenements', 'idEvenements')->onDelete('cascade');
            $table->string('numeroTicket', 50)->unique();
            $table->string('statut', 20)->default('valide'); // valide, utilise, annule
            $table->dateTime('dateAchat');
            $table->dateTime('dateUtilisation')->nullable();
            $table->string('typePlace', 50); // tribune, pelouse, etc.
            $table->string('numeroPlace', 20)->nullable();
            $table->decimal('prixPaye', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}; 