<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventoRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_registros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50);
            $table->string('ap_paterno',50);
            $table->string('ap_materno',50);
            $table->string('clv_lada',3)->default('52');
            $table->string('celular',10);
            $table->date('f_nacimiento');
            $table->string('email')->nullable();
            $table->integer('asist_adult')->default(1);
            $table->integer('asist_kid')->default(0);
            $table->integer('extra_adult')->default(0);
            $table->integer('extra_kid')->default(0);
            $table->string('rfc',10)->nullable();
            $table->boolean('is_cliente')->default(0);
            $table->integer('cliente_id')->nullable();
            $table->string('fraccionamiento')->nullable();
            $table->string('evento');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('evento_registros');
    }
}
