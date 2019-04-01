<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtapasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etapas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fraccionamiento_id');
            $table->string('num_etapa');
            $table->date('f_ini')->nullable();
            $table->date('f_fin')->nullable();
            $table->unsignedInteger('personal_id');
            $table->string('archivo_reglamento',100)->nullable();
            $table->string('plantilla_carta_servicios',100)->nullable();         
            $table->double('costo_mantenimiento')->nullable();
            $table->timestamps();

            $table->foreign('fraccionamiento_id')->references('id')->on('fraccionamientos');
            $table->foreign('personal_id')->references('id')->on('personal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etapas');
    }
}
