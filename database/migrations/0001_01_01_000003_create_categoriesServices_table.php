<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categoriesServices', function (Blueprint $table) {
            $table->id('idCategoriesServices');
            $table->string('nom');
            $table->text('descriptionCategoriesServices');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categoriesServices');
    }
};