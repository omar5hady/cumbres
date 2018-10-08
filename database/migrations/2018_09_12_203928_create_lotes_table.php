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
            $table->boolean('lote_comercial')->default(0);
            $table->string('comentarios')->nullable();

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');
            $table->foreign('etapa_id')->references('id')->on('etapas'); 
            $table->foreign('modelo_id')->references('id')->on('modelos');
            $table->foreign('empresa_id')->references('id')->on('empresas');

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
