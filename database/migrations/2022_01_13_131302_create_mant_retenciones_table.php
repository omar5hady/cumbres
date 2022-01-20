<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMantRetencionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mant_retenciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mantenimiento_id');
            $table->date('fecha_retencion');
            $table->float('importe',8,2)->default(0);
            $table->boolean('status')->default(0);
            $table->string('fecha_real')->nullable();
            $table->string('autorizacion')->nullable();

            $table->foreign('mantenimiento_id')->references('id')->on('mant_vehiculos')->onDelete('cascade');
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
        Schema::dropIfExists('mant_retenciones');
    }
}
