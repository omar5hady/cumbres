<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_lotes', function (Blueprint $table) {
            
            $table->increments('id');

            $table->unsignedInteger('cotizacion_lotes_id')->nullable();
            $table->foreign('cotizacion_lotes_id')->references('id')->on('cotizacion_lotes');

            $table->integer('folio')->default(0)->nullable();
            $table->integer('pago')->default(1)->nullable();

            $table->double('cantidad',10,2)->default(0)->nullable();
            $table->date('fecha')->nullable();
            $table->double('descuento',10,2)->default(0)->nullable();

            $table->integer('dias')->nullable();

            $table->double('descuento_porc')->default(0)->nullable();
            $table->double('interes_monto',10,2)->default(0)->nullable();

            $table->double('total_a_pagar',10,2)->default(0)->nullable();

            $table->double('saldo',10,2)->default(0)->nullable();

            $table->boolean('estatus_pagado')->default(0)->nullable();

            $table->integer('pagare_id')->nullable();

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
        Schema::dropIfExists('pagos_lotes');
    }
}
