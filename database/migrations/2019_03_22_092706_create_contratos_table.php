<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->double('infonavit')->default(0)->nullable();
            $table->double('fovisste')->default(0)->nullable();
            $table->double('comision_apertura')->default(0)->nullable();
            $table->double('investigacion')->default(0)->nullable();
            $table->double('avaluo')->default(0)->nullable();
            $table->double('avaluo_cliente')->default(0)->nullable();
            $table->double('prima_unica')->default(0)->nullable();
            $table->double('escrituras')->default(0)->nullable();
            $table->double('credito_neto')->default(0)->nullable();
            $table->double('monto_total_credito')->default(0)->nullable();
            $table->double('total_pagar')->default(0)->nullable();
            $table->double('enganche_total')->default(0)->nullable();
            $table->boolean('status')->default(1)->nullable();
            $table->date('fecha_status')->nullable();
            $table->date('fecha')->nullable();
            $table->integer('avance_lote')->nullable()->default(0); 

            $table->string('direccion_empresa',80)->nullable();
            $table->integer('cp_empresa')->nullable();
            $table->string('colonia_empresa',80)->nullable();
            $table->string('estado_empresa',80)->nullable();
            $table->string('ciudad_empresa',80)->nullable();
            $table->string('telefono_empresa',10)->nullable();
            $table->string('ext_empresa',5)->nullable();

            $table->string('direccion_empresa_coa',80)->nullable();
            $table->integer('cp_empresa_coa')->nullable();
            $table->string('colonia_empresa_coa',80)->nullable();
            $table->string('estado_empresa_coa',80)->nullable();
            $table->string('ciudad_empresa_coa',80)->nullable();
            $table->string('telefono_empresa_coa',10)->nullable();
            $table->string('ext_empresa_coa',5)->nullable();
            $table->string('observacion')->nullable();

            $table->date('avaluo_preventivo')->nullable();
            $table->date('aviso_prev')->nullable();
            $table->boolean('integracion')->default(0);
            
            
            $table->foreign('id')->references('id')->on('creditos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}
