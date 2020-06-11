<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaDepositosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_depositos', function (Blueprint $table) {
            $table->increments('id');

            $table->double('monto')->default(0);
            $table->string('archivo');

            $table->unsignedInteger('id_depositos');
            $table->foreign('id_depositos')->references('id')->on('depositos');
            
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
        Schema::dropIfExists('factura_depositos');
    }
}
