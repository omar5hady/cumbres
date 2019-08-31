<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvaluosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaluos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->date('fecha_solicitud')->nullable();
            $table->double('valor_requerido')->default(0);
            $table->string('observacion')->nullable();
            $table->date('fecha_recibido')->nullable();
            $table->double('resultado')->default(0);
            $table->string('pdf')->nullable();
            $table->timestamps();


            $table->foreign('contrato_id')->references('id')->on('contratos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaluos');
    }
}
