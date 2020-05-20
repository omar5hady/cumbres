<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsAvaluosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_avaluos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('avaluo_id');
            $table->foreign('avaluo_id')->references('id')->on('avaluos');
            $table->string('observacion');
            $table->string('usuario');
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
        Schema::dropIfExists('obs_avaluos');
    }
}
