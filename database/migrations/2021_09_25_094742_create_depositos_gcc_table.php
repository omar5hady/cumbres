<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositosGccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depositos_gcc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('lote_id');
            $table->double('monto')->default(0);
            $table->string('cuenta',50);
            $table->string('cheque')->nullable();
            $table->date('fecha')->nullable();
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
        Schema::dropIfExists('depositos_gcc');
    }
}
