<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionesModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versiones_modelos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('modelo_id');
            $table->string('archivo');
            $table->string('version');
            $table->timestamps();

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
        Schema::dropIfExists('versiones_modelos');
    }
}
