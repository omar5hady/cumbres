<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_vacations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->date('f_ini');
            $table->date('f_fin');
            $table->string('status')->default('pendiente');
            $table->string('nota')->nullable();
            $table->integer('vacation_id');
            $table->float('dias_tomados',8,2)->default(0);
            $table->float('dias_elegidos',8,2)->default(0);
            $table->float('dias_disponibles',8,2)->default(0);
            $table->float('dias_festivos',8,2)->default(0);
            $table->float('saldo',8,2)->default(0);
            $table->integer('jefe_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hist_vacations');
    }
}
