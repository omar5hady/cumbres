<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaquetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fraccionamiento_id');
            $table->unsignedInteger('etapa_id');
            $table->string('nombre');
            $table->date('v_ini')->nullable();
            $table->date('v_fin')->nullable();
            $table->double('costo');
            $table->text('descripcion');
            $table->text('desc_equipamiento')->nullable();
            $table->timestamps();

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');
            $table->foreign('etapa_id')->references('id')->on('etapas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paquetes');
    }
}
