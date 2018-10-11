<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreciosModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios_modelos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('precio_etapa_id');
            $table->unsignedInteger('modelo_id');
            $table->float('precio_modelo');
            $table->timestamps();

            $table->foreign('precio_etapa_id')->references('id')->on('precios_etapas');
            $table->foreign('modelo_id')->references('id')->on('modelos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precios_modelos');
    }
}
