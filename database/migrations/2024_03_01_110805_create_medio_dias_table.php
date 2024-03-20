<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedioDiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medio_dias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hist_id');
            $table->string('fecha');
            $table->integer('medio_dia')->default(0);
            $table->integer('horaio')->default(0);
            $table->timestamps();

            $table->foreign('hist_id')->references('id')->on('hist_vacations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medio_dias');
    }
}
