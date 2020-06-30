<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreciosTerrenosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios_terrenos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('etapa_id');
            $table->foreign('etapa_id')->references('id')->on('etapas');

            $table->boolean('estatus')->default(1);

            $table->double('precio_m2', 10, 2)->default(0);

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
        Schema::dropIfExists('precios_terrenos');
    }
}
