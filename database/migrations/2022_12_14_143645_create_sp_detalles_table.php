<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solic_id');
            $table->string('obra');
            $table->string('sub_obra')->nullable();
            $table->string('cargo',200);
            $table->string('concepto',255);
            $table->text('observacion');
            $table->boolean('tipo_mov')->default(0);//0 Anticipo, //1 Liquidacion //2 Pago partes
            $table->double('total',10,2)->default(0);
            $table->double('pago',10,2)->default(0);
            $table->double('saldo',10,2)->default(0);
            $table->boolean('status')->default(1); //1 Pendiente 0 Liquidado
            $table->integer('pendiente_id')->nullable();
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
        Schema::dropIfExists('sp_detalles');
    }
}
