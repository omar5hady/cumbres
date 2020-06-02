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
            $table->increments('id');
            $table->integer('mes');
            $table->integer('anio');
            $table->double('total_pagado');
            $table->double('total_comision');
            $table->double('apoyo_mes');
            
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
