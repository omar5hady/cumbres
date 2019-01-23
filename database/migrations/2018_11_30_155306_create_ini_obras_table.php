<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIniObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ini_obras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fraccionamiento_id');
            $table->unsignedInteger('contratista_id');
            $table->date('f_ini')->nullable();
            $table->date('f_fin')->nullable();
            $table->string('clave');
            $table->double('total_costo_directo');
            $table->double('total_costo_indirecto');
            $table->double('total_importe');
            $table->float('anticipo');
            $table->float('costo_indirecto_porcentaje')->default(0);
            $table->double('total_anticipo');
            $table->timestamps();

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');
            $table->foreign('contratista_id')->references('id')->on('contratistas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ini_obras');
    }
}
