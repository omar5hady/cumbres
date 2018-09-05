<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('acabado');
            $table->integer('tipo');
            $table->unsignedInteger('fraccionamiento_id');
            $table->float('terreno');
            $table->float('construccion');
            $table->string('archivo');
            $table->string('planta');


            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');

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
        Schema::dropIfExists('modelos');
    }
}
