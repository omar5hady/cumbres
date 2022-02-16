<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('int_cobros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->double('valor_terreno',10,2)->default(0);
            $table->double('valor_const',10,2)->default(0);
            $table->float('porcentaje_terreno',8,2)->default(0);
            $table->float('porcentaje_construccion',8,2)->default(0);
            $table->double('valor_escrituras',10,2)->default(0);
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
        Schema::dropIfExists('int_cobros');
    }
}
