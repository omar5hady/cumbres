<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatDetallesSubconceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_detalles_subconceptos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_gral');
            $table->string('subconcepto',75);

            $table->foreign('id_gral')->references('id')->on('cat_detalles_generales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_detalles_subconceptos');
    }
}
