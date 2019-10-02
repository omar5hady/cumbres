<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCocinaOtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cocina_otras', function (Blueprint $table) {
            $table->unsignedInteger('id');
            //Estufa
            $table->boolean('estufa_instalacion')->default(1)->nullable();
            $table->boolean('estufa_pzas_extra')->default(1)->nullable();
            $table->boolean('estufa_manuales')->default(1)->nullable();
            $table->boolean('estufa_danos')->default(1)->nullable();
            //Tarja
            $table->boolean('tarja_danos')->default(1)->nullable();
            $table->boolean('tarja_pzas_extra')->default(1)->nullable();

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
        Schema::dropIfExists('cocina_otras');
    }
}
