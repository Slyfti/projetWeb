<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('idservices');
            $table->string('nom');
            $table->foreignId('idCategorie')->constrained('categoriesServices', 'idCategoriesServices');
            $table->text('descriptionServices');
            $table->string('image')->nullable();
            $table->boolean('estActif')->default(true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};