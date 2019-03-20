<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('prospecto_id')->nullable();
            $table->integer('num_dep_economicos')->nullable();
            $table->string('tipo_economia')->nullable();
            $table->string('nombre_primera_ref')->nullable();
            $table->string('telefono_primera_ref')->nullable();
            $table->string('celular_primera_ref')->nullable();
            $table->string('nombre_segunda_ref')->nullable();
            $table->string('telefono_segunda_ref')->nullable();
            $table->string('celular_segunda_ref')->nullable();
            $table->string('etapa')->nullable();
            $table->string('manzana')->nullable();
            $table->string('num_lote')->nullable();
            $table->string('modelo')->nullable();
            $table->double('precio_base')->nullable();
            $table->float('superficie')->nullable();
            $table->float('terreno_excedente')->nullable();
            $table->double('precio_terreno_excedente')->nullable();
            $table->string('promocion')->nullable();
            $table->text('descripcion_promocion')->nullable();
            $table->double('descuento_promocion')->nullable();
            $table->string('paquete')->nullable();
            $table->text('descripcion_paquete')->nullable();
            $table->double('costo_paquete')->nullable();
            $table->double('precio_venta')->nullable();
            $table->integer('plazo')->nullable();
            $table->double('credito_solic')->nullable();
            $table->boolean('status')->default(1)->nullable();
            $table->unsignedInteger('lote_id')->nullable();

            $table->timestamps();
            
            $table->foreign('prospecto_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditos');
    }
}

