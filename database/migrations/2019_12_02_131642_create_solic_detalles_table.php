<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solic_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('contratista_id');
            $table->string('cliente',80);
            $table->integer('dias_entrega');
            $table->boolean('lunes')->default(0);
            $table->boolean('martes')->default(0);
            $table->boolean('miercoles')->default(0);
            $table->boolean('jueves')->default(0);
            $table->boolean('viernes')->default(0);
            $table->boolean('sabado')->default(0);
            $table->string('horario',80);
            $table->string('celular',10);
            $table->double('costo')->default(0);
            $table->boolean('status')->default(0);
            $table->string('nom_contrato')->nullable();

            $table->date('fecha_program')->nullable();
            $table->time('hora_program')->nullable();
            $table->timestamps();

            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('contratista_id')->references('id')->on('contratistas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solic_detalles');
    }
}
