<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesPreviosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_previos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rev_previas_id');
            $table->integer('identificador');
            $table->string('concepto',40);
            $table->string('observacion',191);
            $table->timestamps();

            $table->foreign('rev_previas_id')->references('id')->on('revisiones_previas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_previos');
    }
}
