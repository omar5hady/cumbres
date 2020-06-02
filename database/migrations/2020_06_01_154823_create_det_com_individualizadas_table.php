<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetComIndividualizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_com_individualizadas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('detalle_id');
            $table->foreign('detalle_id')->references('id')->on('det_com_ventas');
            $table->double('pago')->default(0);
            
            $table->unsignedInteger('comision_id')->nullable();
            $table->foreign('comision_id')->references('id')->on('comisiones_ventas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('det_com_individualizadas');
    }
}
