<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaniasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campanias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_campania');
            $table->string('medio_digital');
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->double('presupuesto',10,3)->default(0);
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
        //
    }
}
