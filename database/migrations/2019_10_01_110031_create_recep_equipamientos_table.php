<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecepEquipamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recep_equipamientos', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->date('fecha_revision')->nullable();
            $table->boolean('resultado')->default(1)->nullable();
            $table->string('supervisor');

            $table->timestamps();

            $table->foreign('id')->references('id')->on('solic_equipamientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recep_equipamientos');
    }
}
