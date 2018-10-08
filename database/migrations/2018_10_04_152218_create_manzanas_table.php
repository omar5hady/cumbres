<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManzanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manzanas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fraccionamiento_id');
            $table->string('manzana')->nullable();

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');
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
        Schema::dropIfExists('manzanas');
    }
}
