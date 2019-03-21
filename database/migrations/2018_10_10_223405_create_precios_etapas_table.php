<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreciosEtapasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios_etapas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fraccionamiento_id');
            $table->unsignedInteger('etapa_id');
            $table->double('precio_excedente',10,2);
            $table->timestamps();

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos')->onDelete('cascade');
            $table->foreign('etapa_id')->references('id')->on('etapas')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precios_etapas');
    }
}
