<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiaFestivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dia_festivos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->string('nombre', 100);
            $table->boolean('medio_dia')->default(0); // 0 = dia completo, 1 = medio dia
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
        Schema::dropIfExists('dia_festivos');
    }
}
