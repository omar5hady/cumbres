<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsBonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_bonos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bono_id');
            $table->foreign('bono_id')->references('id')->on('bonos_recomendados');
            $table->string('observacion');
            $table->string('usuario');
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
        Schema::dropIfExists('obs_bonos');
    }
}
