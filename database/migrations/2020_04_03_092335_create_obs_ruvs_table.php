<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsRuvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_ruvs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ruv_id');
            $table->string('observacion')->nullable();
            $table->string('usuario')->nullable();
            $table->timestamps();

            $table->foreign('ruv_id')->references('id')->on('ruvs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obs_ruvs');
    }
}
