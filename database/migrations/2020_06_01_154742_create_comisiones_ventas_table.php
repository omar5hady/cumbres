<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComisionesVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones_ventas', function (Blueprint $table) {
            $table->increments('id');//
            $table->integer('mes');//
            $table->integer('anio');//
            $table->double('total_pagado'); //total_a_pagar//
            $table->double('total_comision'); // total//
            $table->double('total_porPagar')->default(0); //total_a_pagar

            $table->double('total_bono')->default(0); //total_bono
            $table->double('total_anticipo')->default(0); //total_anticipo
            $table->double('apoyo_mes'); //apoyo//
            $table->boolean('tipo_vendedor')->default(0); 

            $table->double('restanteAnt')->default(0); //restante
            
            $table->unsignedInteger('asesor_id');
            $table->foreign('asesor_id')->references('id')->on('vendedores');
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
        Schema::dropIfExists('comisiones_ventas');
    }
}
