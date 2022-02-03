<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_salidas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->string('concepto',60);
            $table->integer('cantidad')->default(0);
            $table->integer('tipo_producto');
            $table->integer('user_id');
            $table->boolean('tipo_mov')->default(1);
            $table->string('oficina',60);
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
        Schema::dropIfExists('inv_salidas');
    }
}
