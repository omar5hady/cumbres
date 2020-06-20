<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetComCambiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_com_cambios', function (Blueprint $table) {
            $table->increments('id');
            $table->double('monto')->default(0);
            $table->string('descripcion')->default(0);

            $table->unsignedInteger('comision_id')->nullable();
            $table->foreign('comision_id')->references('id')->on('comisiones_ventas');
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
        Schema::dropIfExists('det_com_cambios');
    }
}
