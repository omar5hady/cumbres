<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licencias', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->date('f_planos')->nullable();
            $table->date('f_ingreso')->nullable(); 
            $table->date('f_salida')->nullable(); 
            $table->string('num_licencia')->nullable(); 
             
            $table->foreign('id')->references('id')->on('lotes'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licencias');
    }
}
