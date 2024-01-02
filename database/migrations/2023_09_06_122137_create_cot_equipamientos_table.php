<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotEquipamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cot_equipamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lote_id');
            $table->double('precio_venta',10,2)->default(0);
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
            $table->string('observacion')->nullable();
            $table->unsignedInteger('cliente_id')->nullable();
            $table->integer('usuario')->nullable();
            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('cliente_id')->references('id')->on('personal');
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
        Schema::dropIfExists('cot_equipamientos');
    }
}
