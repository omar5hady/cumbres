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
            $table->date('fecha_exp')->nullable();
            $table->float('porcentaje_exp');
            $table->float('porcentaje_casa')->nullable();
            $table->float('comisionReal')->default(0);
            $table->float('extra')->default(0);
            $table->double('total')->nullable();
            $table->double('bono')->nullable();
            $table->date('fecha_bono')->nullable();
            $table->double('anticipo')->nullable();
            $table->unsignedInteger('idComision')->nullable();
            $table->foreign('idComision')->references('id')->on('comisiones');
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
