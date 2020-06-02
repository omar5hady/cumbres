<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetComCanceladasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_com_canceladas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('detalle_id');
            $table->foreign('detalle_id')->references('id')->on('det_com_ventas');
            $table->double('monto')->default(0);
            $table->double('bono_cancel')->default(0);
            
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
        Schema::dropIfExists('det_com_canceladas');
    }
}
