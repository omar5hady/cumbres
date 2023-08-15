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
            $table->string('descripcion_corta')->nullable();
            $table->string('calle1')->nullable();
            $table->string('calle2')->nullable();
            $table->string('descripcion_larga')->nullable();
            $table->double('total_superficie')->nullable();
            $table->boolean('iva')->default(0);
            $table->string('tipo',30)->nullable();
            $table->integer('num_torres')->nullable();

            $table->string('emp_constructora')->default('Grupo Constructor Cumbres');
            $table->float('porc_garantia_ret', 8, 2)->default(0);
            $table->double('garantia_ret', 10, 2)->default(0);
            $table->integer('num_casas')->default(0);

            $table->string('documento')->nullable();
            $table->string('adendum')->nullable();
            $table->string('registro_obra')->nullable();

            $table->string('direccion_proy')->nullable();
            $table->boolean('fin_estimaciones')->default(0);

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
