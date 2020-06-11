<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaExpedientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_expedientes', function (Blueprint $table) {
            $table->increments('id');

            $table->double('monto')->default(0);
            $table->string('archivo');

            $table->unsignedInteger('id_expedientes');
            $table->foreign('id_expedientes')->references('id')->on('expedientes');

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
        Schema::dropIfExists('factura_expedientes');
    }
}
