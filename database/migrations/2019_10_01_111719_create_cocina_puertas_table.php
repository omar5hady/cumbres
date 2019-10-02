<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCocinaPuertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cocina_puertas', function (Blueprint $table) {
            $table->unsignedInteger('id');
            //Puertas
            $table->boolean('puerta_danos')->default(1)->nullable();
            $table->boolean('puerta_tornillos')->default(1)->nullable();
            $table->boolean('puerta_abatimiento')->default(1)->nullable();
            $table->boolean('puerta_limpieza')->default(1)->nullable();
            $table->boolean('puerta_jaladera')->default(1)->nullable();
            $table->boolean('puerta_gomas')->default(1)->nullable();
            //Cajones
            $table->boolean('cajones_uniones')->default(1)->nullable();
            $table->boolean('cajones_silicon')->default(1)->nullable();
            $table->boolean('cajones_limpieza')->default(1)->nullable();
            $table->boolean('cajones_jaladeras')->default(1)->nullable();
            $table->boolean('cajones_cantos')->default(1)->nullable();
            $table->boolean('cajones_rieles')->default(1)->nullable();
            $table->boolean('cajones_estantes')->default(1)->nullable();
            $table->boolean('cajones_pzas_comp')->default(1)->nullable();
            //Alacenas
            $table->boolean('alacena_entrepano')->default(1)->nullable();
            $table->boolean('alacena_pistones')->default(1)->nullable();
            $table->boolean('alacena_jaladeras')->default(1)->nullable();
            $table->boolean('alacena_micro')->default(1)->nullable();
            $table->boolean('alacena_cantos')->default(1)->nullable();
            $table->boolean('alacena_limpieza')->default(1)->nullable();
            $table->boolean('alacena_parches')->default(1)->nullable();

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
        Schema::dropIfExists('cocina_puertas');
    }
}
