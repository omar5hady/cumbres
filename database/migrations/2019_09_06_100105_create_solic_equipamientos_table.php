<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicEquipamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solic_equipamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lote_id');
            $table->unsignedInteger('contrato_id');
            $table->date('fecha_solicitud')->nullable();
            $table->double('costo')->nullable();
            $table->date('fecha_colocacion')->nullable();
            $table->double('anticipo')->nullable();
            $table->date('fecha_anticipo')->nullable();
            $table->unsignedInteger('equipamiento_id');

            $table->double('liquidacion')->nullable();
            $table->date('fecha_liquidacion')->nullable();
            $table->string('num_factura', 8)->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();

            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('contrato_id')->references('id')->on('contratos');
            $table->foreign('equipamiento_id')->references('id')->on('equipamientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solic_equipamientos');
    }
}
