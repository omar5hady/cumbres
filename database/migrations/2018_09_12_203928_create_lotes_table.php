<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fraccionamiento_id'); //proyecto
            $table->unsignedInteger('etapa_id');
            $table->string('manzana');
            $table->integer('num_lote');
            $table->string('sublote')->nullable();
            $table->unsignedInteger('modelo_id');
            $table->unsignedInteger('empresa_id')->default(1); //vendedor
            $table->string('calle'); /** ubicacion */
            $table->string('numero');
            $table->string('interior')->nullable();
            $table->float('terreno'); /** Dimensiones */
            $table->float('construccion');
            $table->boolean('casa_muestra')->default(0);
            $table->boolean('casa_renta')->default(0);
            $table->float('precio_renta',8,2)->default(0);
            $table->boolean('lote_comercial')->default(0);
            $table->boolean('ini_obra')->default(0);
            $table->boolean('habilitado')->default(0);
            $table->boolean('regimen_condom')->default(0);
            $table->integer('apartado')->default(0);
            $table->boolean('contrato')->default(0);
            $table->string('comentarios')->nullable();
            $table->string('clv_catastral',40)->nullable();
            $table->integer('etapa_servicios')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->unsignedInteger('arquitecto_id')->nullable();
            $table->string('credito_puente',50)->nullable();
            $table->date('siembra')->nullable();
            $table->date('ehl_solicitado')->nullable();
            $table->string('aviso',11)->default(0);
            $table->double('obra_extra')->default(0);
            $table->double('ajuste')->default(0);
            $table->date('fecha_termino_ventas')->nullable();
            $table->date('fin_obra')->nullable();
            $table->string('paquete')->nullable();
            $table->boolean('firmado')->default(0);
            $table->float('extra')->default(0);
            $table->float('extra_ext')->default(0);
            $table->string('emp_terreno')->default('Grupo Constructor Cumbres');
            $table->string('emp_constructora')->default('Grupo Constructor Cumbres');

            $table->string('nombre_archivo')->nullable();
            $table->string('colindancias')->nullable();
            $table->float('indivisos',8,2)->default(0);

            $table->date('fecha_entrega_obra')->nullable();

            $table->integer('num_inicio')->default(0);

            $table->string('paq_ruv',50)->nullable();

            $table->boolean('gas_nat')->default(0);


            ///////// Datos para precio //////////////////
            $table->double('precio_base')->default(0);
            $table->double('excedente_terreno')->default(0);
            $table->double('sobreprecio')->default(0);

            $table->integer('puente_id')->nullable();
            $table->string('condiciones',100)->default('Nueva');

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');
            $table->foreign('etapa_id')->references('id')->on('etapas');
            $table->foreign('modelo_id')->references('id')->on('modelos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('arquitecto_id')->references('id')->on('personal');

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
        Schema::dropIfExists('lotes');
    }
}
