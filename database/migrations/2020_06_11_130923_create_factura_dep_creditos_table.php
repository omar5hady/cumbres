<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaDepCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_dep_creditos', function (Blueprint $table) {
            $table->increments('id');

            $table->double('monto')->default(0);
            $table->string('archivo');

            $table->unsignedInteger('id_dep_creditos');
            $table->foreign('id_dep_creditos')->references('id')->on('dep_creditos');

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
        Schema::dropIfExists('factura_dep_creditos');
    }
}
