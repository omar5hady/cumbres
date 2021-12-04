<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vehiculo',50);
            $table->integer('modelo');
            $table->string('marca',30);
            $table->string('clave',10)->nullable();
            $table->string('placas',7);
            $table->string('numero_serie',20)->nullable();
            $table->string('numero_motor',10)->nullable();
            $table->integer('responsable_id');
            $table->string('empresa')->default('Grupo Constructor Cumbres');
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
        Schema::dropIfExists('vehiculos');
    }
}
