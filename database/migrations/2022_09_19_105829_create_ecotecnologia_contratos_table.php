<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcotecnologiaContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecotecnologia_contratos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('credito_id');
            $table->string('ecotecnologia');
            $table->float('precio',10,2)->default(0);
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
        Schema::dropIfExists('ecotecnologia_contratos');
    }
}
