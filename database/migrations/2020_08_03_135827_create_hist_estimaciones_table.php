<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistEstimacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_estimaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('estimacion_id')->nullable();
            $table->foreign('estimacion_id')->references('id')->on('estimaciones');
            $table->integer('num_estimacion');
            $table->float('vol');
            $table->double('costo',10,2)->default(0);
            $table->double('total_estimacion',10,2)->default(0);
            $table->date('ini')->nullable();
            $table->date('fin')->nullable();
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
        Schema::dropIfExists('hist_estimaciones');
    }
}
