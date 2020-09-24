<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotesIndividualesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //tabla usada para la calculadora de lotes
        Schema::create('lotes_individuales', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('etapa_id');
            $table->foreign('etapa_id')->references('id')->on('etapas');

            $table->double('costom2');

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
        Schema::dropIfExists('lotes_individuales');
    }
}
