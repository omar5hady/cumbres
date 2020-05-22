<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsCobroCreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_cobro_credito', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('credito_id');
            $table->text('comentario');
            $table->string('usuario',50);
            $table->timestamps();

            $table->foreign('credito_id')->references('id')->on('inst_seleccionadas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obs_cobro_credito');
    }
}
