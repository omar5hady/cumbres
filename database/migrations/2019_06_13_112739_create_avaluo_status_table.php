<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvaluoStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaluo_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('avaluo_id');
            $table->string('status')->nullable();
            $table->string('observacion')->nullable();
            $table->timestamps();

            $table->foreign('avaluo_id')->references('id')->on('avaluos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaluo_status');
    }
}
