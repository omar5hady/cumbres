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
            $table->string('plantilla_telecom',100)->nullable();
            $table->string('empresas_telecom',120)->nullable();
            $table->string('empresas_telecom_satelital',120)->nullable();
            $table->string('carta_bienvenida',191)->nullable();
            
            $table->string('num_cuenta_admin',50)->nullable();
            $table->string('clabe_admin',50)->nullable();
            $table->string('sucursal_admin',50)->nullable();
            $table->string('titular_admin',100)->nullable();
            $table->string('banco_admin',50)->nullable();
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
