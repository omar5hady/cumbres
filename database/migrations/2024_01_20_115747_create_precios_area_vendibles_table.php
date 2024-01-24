<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreciosAreaVendiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios_area_vendibles', function (Blueprint $table) {
            $table->increments('id');
            $table->float('habitacional')->default(0);
            $table->float('comercial')->default(0);
            $table->float('reserva')->default(0);
            $table->string('usuario');
            $table->unsignedInteger('fraccionamiento_id');
            $table->timestamps();

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precios_area_vendibles');
    }
}
