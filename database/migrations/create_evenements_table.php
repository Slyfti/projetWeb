<?php
// database/migrations/2024_03_26_000007_create_evenements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id('idEvenements');
            $table->string('nom', 100);
            $table->dateTime('dateEvenements')->nullable();
            $table->text('descriptionEvenements')->nullable();
            $table->string('typeEvents', 100);
            $table->string('equipeDomicile', 100);
            $table->string('equipeExterieur', 100);
            $table->float('prix');
            $table->integer('Disponiblilite');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evenements');
    }
};