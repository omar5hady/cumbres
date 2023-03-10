<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_contactos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prospecto_id')->nullable();
            $table->integer('lead_id')->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('celular',10)->nullable();
            $table->string('email',50)->nullable();
            $table->string('usuario',50)->nullable();
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
        Schema::dropIfExists('hist_contactos');
    }
}
