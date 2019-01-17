<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lote_id');
            $table->unsignedInteger('partida_id');
            $table->float('avance')->default(0);
            $table->float('avance_porcentaje')->default(0);
            $table->boolean('cambio_avance')->default(0); //campo bandera para validar si el avance es menor al que ya estaba

            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('partida_id')->references('id')->on('partidas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avances');
    }
}
