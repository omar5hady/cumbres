<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComisionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mes');
            $table->integer('anio');
            $table->double('total');
            $table->double('bono')->default(0);
            $table->double('aPagar')->default(0);
            $table->integer('num_ventas');
            $table->integer('num_cancelaciones')->default(0);
            $table->double('cobrado')->default(0);
            $table->unsignedInteger('asesor_id');
            $table->foreign('asesor_id')->references('id')->on('vendedores');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comisiones');
    }
}
