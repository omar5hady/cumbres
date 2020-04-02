<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_pagos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('solicitud_id')->nullable(false);
            $table->foreign('solicitud_id')->references('id')->on('solicitudes_pagos')->onDelete('cascade');
            
            $table->string('archivo');
            $table->string('nombre');
            $table->string('usuario');

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
        Schema::dropIfExists('documentos_pagos');
    }
}
