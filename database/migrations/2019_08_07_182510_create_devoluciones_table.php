<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('concepto')->nullable();
            $table->double('monto_cargo')->default(0)->nullable();
            $table->double('devolver')->default(0);
            $table->date('fecha');
            $table->string('cheque');
            $table->string('cuenta');
            $table->string('observaciones')->nullable();
            
            $table->timestamps(); 

            
            $table->foreign('id')->references('id')->on('contratos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devoluciones');
    }
}
