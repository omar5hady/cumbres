<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proveedor_id');
            $table->string('equipamiento',50);
            $table->boolean('activo')->default(1);
            $table->boolean('tipoRecepcion')->default(0);

            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipamientos');
    }
}
