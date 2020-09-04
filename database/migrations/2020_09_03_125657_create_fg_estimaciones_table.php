<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFgEstimacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fg_estimaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aviso_id')->nullable();
            $table->foreign('aviso_id')->references('id')->on('ini_obras');
            $table->integer('cantidad');
            $table->double('monto_fg',10,2)->default(0);
            $table->date('fecha_fg');
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
        Schema::dropIfExists('fg_estimaciones');
    }
}
