<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->date('fecha_program')->nullable();
            $table->time('hora_entrega_prog')->nullable();
            $table->boolean('status')->default(0);
            $table->date('fecha_entrega_real')->nullable();
            $table->time('hora_entrega_real')->nullable();
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
        Schema::dropIfExists('entregas');
    }
}
