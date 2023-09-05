<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatEquipamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_equipamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('modelo_id');
            $table->double('cocina_tradicional',10,2)->default(0);
            $table->double('vestidor',10,2)->default(0);
            $table->double('closets',10,2)->default(0);
            $table->double('canceles',10,2)->default(0);
            $table->double('persianas',10,2)->default(0);
            $table->double('calentador_paso',10,2)->default(0);
            $table->double('calentador_solar',10,2)->default(0);
            $table->double('espejos',10,2)->default(0);
            $table->double('tanque_estacionario',10,2)->default(0);
            $table->double('cocina',10,2)->default(0);
            $table->boolean('status')->default(1);
            $table->foreign('modelo_id')->references('id')->on('modelos');
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
        Schema::dropIfExists('cat_equipamientos');
    }
}
