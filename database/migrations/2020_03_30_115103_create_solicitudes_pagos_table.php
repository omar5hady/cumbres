<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_pagos', function (Blueprint $table) {
            $table->increments('id');

            $table->string('concepto', 191)->comment('Concepto para identificar la orden de pago');
            $table->boolean('orden_compra')->nullable(true)->comment('permite identificar si el registro tiene o no orden de compra 0= no 1 = si ');
            $table->date('orden_vistoBueno')->nullable(true);
            $table->date('autorizacion_orden')->nullable(true);
            $table->string('doc_orden')->nullable(true);
            $table->string('solic_cheque')->nullable(true);
            $table->string('cotizacion')->nullable(true);
            $table->string('pago_partes')->nullable(true);
            $table->string('factura')->nullable(true);
            $table->date('check1')->nullable(true);
            $table->date('check2')->nullable(true);
            $table->date('check3')->nullable(true);
            $table->boolean('status')->nullable(true);
            $table->date('fecha_status')->nullable(true);
            
            $table->unsignedInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('solicitudes_pagos');
    }
}
