<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruvs', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('empresa_id')->nullable();
            $table->unsignedInteger('user_siembra')->nullable();

            $table->date('fecha_siembra')->nullable();
            $table->date('fecha_carga')->nullable(); 
            $table->integer('num_cuv')->nullable(); 
            $table->date('fecha_asignacion')->nullable();
            $table->date('fecha_revision')->nullable();
            $table->date('fecha_dtu')->nullable();
             
            $table->foreign('id')->references('id')->on('lotes'); 
            $table->foreign('user_siembra')->references('id')->on('personal');
            $table->foreign('empresa_id')->references('id')->on('empresas_verificadoras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruvs');
    }
}
