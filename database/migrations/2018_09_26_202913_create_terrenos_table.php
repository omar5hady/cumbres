<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerrenosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terrenos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fraccionamiento_id');
            $table->unsignedInteger('etapa_id');
            $table->unsignedInteger('manzana_id');
            $table->Integer('num_lote');
            $table->unsignedInteger('empresa_id')->default(1); //vendedor
            $table->string('calle'); /** ubicacion */
            $table->string('numero');
            $table->string('interior')->nullable();
            $table->float('terreno'); /** Dimensiones */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terrenos');
    }
}
