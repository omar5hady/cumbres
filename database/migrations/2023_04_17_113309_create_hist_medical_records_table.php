<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_medical_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('record_id');
            $table->date('fecha');
            $table->float('peso',8,2);
            $table->float('imc',8,2)->nullable();
            $table->text('tratamiento_act')->nullable();
            $table->text('medic_controlado')->nullable();
            $table->text('observacion')->nullable();
            //PrevenIMSS
            $table->float('cintura',8,2)->nullable();
            $table->float('glucosa',8,2)->nullable();
            $table->float('trigliceridos',8,2)->nullable();
            $table->float('colesterol',8,2)->nullable();
            $table->string('presion_arterial')->nullable();
            //HISTORIAL MEDICO
            $table->boolean('diabetes')->default(0);
            $table->string('diabetes_esp',50)->nullable();
            $table->boolean('hipertension')->default(0);
            $table->string('hipertension_esp',50)->nullable();
            $table->boolean('epileptico')->default(0);
            $table->string('epileptico_esp',50)->nullable();
            $table->boolean('cardiaco')->default(0);
            $table->string('cardiaco_esp',50)->nullable();
            $table->boolean('mareos')->default(0);
            $table->string('mareos_esp',50)->nullable();
            $table->boolean('asma')->default(0);
            $table->string('asma_esp',50)->nullable();
            $table->boolean('toxicomanias')->default(0);
            $table->string('toxicomanias_esp',50)->nullable();
            $table->boolean('cirugia')->default(0);
            $table->string('cirugia_esp',50)->nullable();
            $table->boolean('embarazo')->default(0);
            $table->string('embarazo_esp',50)->nullable();
            $table->boolean('transfusion')->default(0);
            $table->string('transfusion_esp',50)->nullable();
            $table->boolean('lesion_musculo')->default(0);
            $table->string('lesion_musculo_esp',50)->nullable();
            $table->boolean('ortopedicos')->default(0);
            $table->string('ortopedicos_esp',50)->nullable();
            $table->boolean('respiratorios')->default(0);
            $table->string('respiratorios_esp',50)->nullable();
            $table->boolean('oftalmicos')->default(0);
            $table->string('oftalmicos_esp',50)->nullable();
            $table->boolean('nariz')->default(0);
            $table->string('nariz_esp',50)->nullable();
            $table->boolean('neurologicos')->default(0);
            $table->string('neurologicos_esp',50)->nullable();
            $table->boolean('hematologicos')->default(0);
            $table->string('hematologicos_esp',50)->nullable();
            $table->boolean('hepaticos')->default(0);
            $table->string('hepaticos_esp',50)->nullable();
            $table->boolean('digestivo')->default(0);
            $table->string('digestivo_esp',50)->nullable();
            $table->boolean('tiroideo')->default(0);
            $table->string('tiroideo_esp',50)->nullable();
            $table->boolean('dermatologico')->default(0);
            $table->string('dermatologico_esp',50)->nullable();
            $table->boolean('inmunologico')->default(0);
            $table->string('inmunologico_esp',50)->nullable();
            $table->boolean('urinario')->default(0);
            $table->string('urinario_esp',50)->nullable();
            $table->boolean('covid')->default(0);
            $table->string('covid_esp',50)->nullable();
            //PSICOLOGICOS/PSIQUIATRICOS
            $table->boolean('cambio_alimentacion')->default(0);
            $table->string('cambio_alimentacion_esp',50)->nullable();
            $table->boolean('aislamiento')->default(0);
            $table->string('aislamiento_esp',50)->nullable();
            $table->boolean('sensacion_vacio')->default(0);
            $table->string('sensacion_vacio_esp',50)->nullable();
            $table->boolean('desesperanza')->default(0);
            $table->string('desesperanza_esp',50)->nullable();
            $table->boolean('irritabilidad')->default(0);
            $table->string('irritabilidad_esp',50)->nullable();
            $table->boolean('pensamientos_cabeza')->default(0);
            $table->string('pensamientos_cabeza_esp',50)->nullable();
            $table->boolean('lastimarse')->default(0);
            $table->string('lastimarse_esp',50)->nullable();
            $table->boolean('alt_sueno')->default(0);
            $table->string('alt_sueno_esp',50)->nullable();
            $table->boolean('agotamiento')->default(0);
            $table->string('agotamiento_esp',50)->nullable();
            $table->boolean('dolores')->default(0);
            $table->string('dolores_esp',50)->nullable();
            $table->boolean('aumento_toxic')->default(0);
            $table->string('aumento_toxic_esp',50)->nullable();
            $table->boolean('humor')->default(0);
            $table->string('humor_esp',50)->nullable();
            $table->boolean('voces')->default(0);
            $table->string('voces_esp',50)->nullable();
            $table->boolean('dif_tareas')->default(0);
            $table->string('dif_tareas_esp',50)->nullable();

            $table->foreign('record_id')->references('id')->on('medical_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hist_medical_records');
    }
}
