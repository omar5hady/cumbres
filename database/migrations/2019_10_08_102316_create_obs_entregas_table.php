<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('entrega_id');
            $table->text('comentario');
            $table->string('usuario',50);
            $table->timestamps();

            $table->foreign('entrega_id')->references('id')->on('entregas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obs_entregas');
    }
}
