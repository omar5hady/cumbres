<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnticiposEstimacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anticipos_estimaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aviso_id')->nullable();
            $table->foreign('aviso_id')->references('id')->on('ini_obras');
            $table->date('fecha_anticipo');
            $table->double('monto_anticipo',10,2)->default(0);
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
        Schema::dropIfExists('anticipos_estimaciones');
    }
}
