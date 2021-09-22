<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevVirtualesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dev_virtuales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->double('monto')->default(0);
            $table->date('fecha');
            $table->string('cheque')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('observaciones')->nullable();

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
        Schema::dropIfExists('dev_virtuales');
    }
}
