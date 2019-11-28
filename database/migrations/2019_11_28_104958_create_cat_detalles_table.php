<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_sub');
            $table->string('detalle',80);

            $table->foreign('id_sub')->references('id')->on('cat_detalles_subconceptos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_detalles');
    }
}
