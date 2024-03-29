<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFraccionamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fraccionamientos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50);
            $table->integer('tipo_proyecto');
            $table->string('calle',100);
            $table->integer('numero')->nullable();
            $table->string('colonia',50)->nullable();
            $table->string('estado',100)->nullable();
            $table->string('ciudad',100)->nullable();
            $table->string('archivo_planos')->nullable();
            $table->string('archivo_escrituras')->nullable();
            $table->string('delegacion')->nullable();
            $table->integer('cp')->nullable();
            $table->string('email_administracion',40)->nullable();
            $table->string('logo_fracc',100)->nullable();
            $table->string('logo_fracc2',255)->nullable();
            $table->date('fecha_ini_venta')->nullable();
            $table->integer('gerente_id')->nullable();
            $table->integer('arquitecto_id')->nullable();
            $table->integer('postventa_id')->nullable();

            $table->text('rest_ambientales')->nullable();
            $table->text('rest_federales')->nullable();
            $table->text('rest_otras')->nullable();

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
        Schema::dropIfExists('fraccionamientos');
    }
}
