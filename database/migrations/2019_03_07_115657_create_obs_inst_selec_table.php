<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsInstSelecTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_inst_selec', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inst_selec_id');
            $table->text('comentario');
            $table->string('usuario',50);
            $table->timestamps();

            $table->foreign('inst_selec_id')->references('id')->on('inst_seleccionadas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obs_inst_selec');
    }
}
