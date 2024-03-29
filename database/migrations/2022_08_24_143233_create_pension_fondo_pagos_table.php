<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePensionFondoPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pension_fondo_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->float('monto',10,2);
            $table->string('concepto');
            $table->boolean('tipo_movimiento')->default(0);
            $table->date('fecha_movimiento');
            $table->boolean('status')->default(0);
            $table->unsignedInteger('fondo_id');
            $table->timestamps();

            $table->foreign('fondo_id')->references('id')->on('pension_fondos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pension_fondo_pagos');
    }
}
