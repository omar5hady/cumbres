<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_contratos', function (Blueprint $table) {
            $table->increments('id');

            $table->double('monto')->default(0);
            $table->string('archivo');

            $table->unsignedInteger('id_contrato');
            $table->foreign('id_contrato')->references('id')->on('lotes');
            
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
        Schema::dropIfExists('factura_contratos');
    }
}
