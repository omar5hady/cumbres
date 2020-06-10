<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetComVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_com_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contratos');
            $table->float('porcentaje_comision');
            $table->double('comision_pagar')->default(0);
            $table->double('iva')->default(0);
            $table->double('isr')->default(0);
            $table->double('retencion')->default(0);
            $table->double('este_pago')->default(0);
            $table->double('por_pagar')->default(0);

            $table->boolean('pendiente')->default(0);
           
            $table->unsignedInteger('comision_id')->nullable();
            $table->foreign('comision_id')->references('id')->on('comisiones_ventas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('det_com_ventas');
    }
}
