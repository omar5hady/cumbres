<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSobrepreciosEtapasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sobreprecios_etapas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('etapa_id');
            $table->unsignedInteger('sobreprecio_id');
            $table->float('sobreprecio');
            $table->timestamps();

            $table->foreign('etapa_id')->references('id')->on('etapas');
            $table->foreign('sobreprecio_id')->references('id')->on('sobreprecios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sobreprecios_etapas');
    }
}
