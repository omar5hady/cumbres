<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_cobros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integracion_id');
            $table->integer('deposito_id')->nullable();
            $table->date('fecha_pago');
            $table->string('banco');
            $table->string('num_cheque')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('clave',13)->nullable();
            $table->string('tarjeta',16)->nullable();
            $table->double('cant_depo',10,2)->default(0);
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
        Schema::dropIfExists('pagos_cobros');
    }
}
