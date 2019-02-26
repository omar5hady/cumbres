<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatosExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_extra', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->boolean('mascota')->default(0)->nullable();
            $table->integer('num_perros')->nullable();
            $table->integer('rang010')->nullable();
            $table->integer('rang1120')->nullable();
            $table->integer('rang21')->nullable();
            $table->boolean('ama_casa')->default(0)->nullable();
            $table->boolean('persona_discap')->default(0)->nullable();
            $table->boolean('silla_ruedas')->default(0)->nullable();
            $table->integer('num_vehiculos')->nullable();
            
            $table->foreign('id')->references('id')->on('creditos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datos_extra');
    }
}
