<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCocinaAcabadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cocina_acabados', function (Blueprint $table) {
            $table->unsignedInteger('id');
            //Cubierta 
            $table->boolean('cubierta_acab_uniones')->default(1)->nullable();
            $table->boolean('cubierta_acab_silicon')->default(1)->nullable();
            $table->boolean('cubierta_acab_cortes')->default(1)->nullable();
            //Puertas
            $table->boolean('puerta_acab_alineados')->default(1)->nullable();
            $table->boolean('puerta_acab_cantos')->default(1)->nullable();
            

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
        Schema::dropIfExists('cocina_acabados');
    }
}
