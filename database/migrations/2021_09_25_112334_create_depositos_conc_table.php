<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositosConcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depositos_conc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('lote_id');
            $table->double('monto')->default(0);
            $table->string('cuenta',50);
            $table->date('fecha')->nullable();
            $table->boolean('devolucion')->default(0);
            $table->string('cheque')->nullable();
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
        Schema::dropIfExists('depositos_conc');
    }
}
