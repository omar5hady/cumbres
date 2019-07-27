<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiquidacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidacion', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->integer('interes_ord');
            $table->integer('interes_mor');
            $table->date('fecha_ini_interes');
            $table->string('nombre_aval')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('nombre_aval2')->nullable();
            $table->string('direccion2')->nullable();
            $table->string('telefono2',10)->nullable();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('expedientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liquidacion');
    }
}
