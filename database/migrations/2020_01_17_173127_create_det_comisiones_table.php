<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetComisionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_comisiones', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->foreign('id')->references('id')->on('contratos');
            $table->date('fecha_anticipo')->nullable();
            $table->date('fecha_exp');
            $table->float('porcentaje_exp');
            $table->float('porcentaje_casa')->nullable();
            $table->float('total')->nullable();
            $table->integer('idComision')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('det_comisiones');
    }
}
