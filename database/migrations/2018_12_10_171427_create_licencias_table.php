<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licencias', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->date('f_planos')->nullable();
            $table->date('f_planos_obra')->nullable();
            $table->date('f_ingreso')->nullable();
            $table->date('f_salida')->nullable();
            $table->string('num_licencia')->nullable();
            $table->unsignedInteger('perito_dro')->nullable();
            $table->integer('avance')->nullable()->default(0);
            $table->date('term_ingreso')->nullable();
            $table->date('term_salida')->nullable();
            $table->boolean('cambios')->nullable()->default(0);
            $table->string('foto_lic')->nullable();
            $table->string('num_acta')->nullable();
            $table->string('foto_acta')->nullable();
            $table->string('foto_predial')->nullable();
            $table->string('modelo_ant')->default('N/A');
            $table->date('visita_avaluo')->nullable();

            $table->date('fecha_licencia')->nullable();
            $table->date('fecha_acta')->nullable();
            $table->date('fecha_predial')->nullable();
            $table->boolean('duenio_id')->nullable();
            $table->string('archivo_esp')->nullable();

            $table->text('colindancias')->nullable();
            $table->date('date_birth')->nullable();

            $table->text('num_escritura')->nullable();
            $table->date('date_escritura')->nullable();
            $table->text('num_notario')->nullable();
            $table->text('distrito_notario')->nullable();
            $table->text('folio_registro')->nullable();

            $table->foreign('id')->references('id')->on('lotes')->onDelete('cascade');
            $table->foreign('perito_dro')->references('id')->on('personal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licencias');
    }
}
