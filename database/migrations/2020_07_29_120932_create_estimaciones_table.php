<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aviso_id')->nullable();
            $table->foreign('aviso_id')->references('id')->on('ini_obras');
            $table->string('partida',191);
            $table->double('pu_prorrateado',10,2)->default(0);
            $table->double('cant_tope',10,2)->default(0);

            $table->string('nivel')->nullable();
            $table->boolean('comun')->default(0);
            $table->float('porc',10,2)->default(0);
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
        Schema::dropIfExists('estimaciones');
    }
}
