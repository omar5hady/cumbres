<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partida',70);
            $table->unsignedInteger('lote_id');
            $table->double('costo')->default(0);
            $table->float('porcentaje')->default(0);
            $table->float('avance')->default(0);
            $table->float('porcentaje_avance')->default(0);

            $table->foreign('lote_id')->references('id')->on('lotes'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partidas');
    }
}
