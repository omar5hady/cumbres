<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvisoPreventivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aviso_preventivos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('notaria_id');
            $table->date('fecha_solicitud')->nullable();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos');
            $table->foreign('notaria_id')->references('id')->on('notarias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aviso_preventivos');
    }
}
