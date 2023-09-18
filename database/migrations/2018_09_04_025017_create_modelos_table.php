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
            $table->integer('tipo');
            $table->unsignedInteger('fraccionamiento_id');
            $table->float('terreno');
            $table->float('construccion');
            $table->string('archivo')->nullable();
            $table->string('ficha_tecnica',255)->nullable();
            $table->string('espec_obra',50)->nullable();
            $table->string('recorrido',191)->nullable();
            $table->string('cat_equipamiento')->nullable();

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
