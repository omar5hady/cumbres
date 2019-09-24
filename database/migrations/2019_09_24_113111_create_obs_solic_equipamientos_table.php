<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsSolicEquipamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_solic_equipamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('solic_id');
            $table->text('comentario');
            $table->string('usuario',50);
            $table->timestamps();

            $table->foreign('solic_id')->references('id')->on('solic_equipamientos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obs_solic_equipamientos');
    }
}
