<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSobrepreciosModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sobreprecios_modelos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lote_id');
            $table->unsignedInteger('sobreprecio_etapa_id');
            $table->timestamps();

            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('sobreprecio_etapa_id')->references('id')->on('sobreprecios_etapas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sobreprecios_modelos');
    }
}
