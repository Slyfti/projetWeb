<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categoriesObjets', function (Blueprint $table) {
            $table->id('idCategoriesObjets');
            $table->string('nom');
            $table->text('descriptionCategoriesObjets');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categoriesObjets');
    }
};