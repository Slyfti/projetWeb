<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zonesStade', function (Blueprint $table) {
            $table->id('idZonesStade');
            $table->string('nom');
            $table->text('descriptionZonesStade');
            $table->integer('capacite');
        });
    }

    public function down()
    {
        Schema::dropIfExists('zonesStade');
    }
};