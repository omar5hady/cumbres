<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsesorExternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asesor_externos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mobiliaria_id');
            $table->string('nombre',60);
            $table->string('apellido',80);
            $table->string('direccion',100)->nullable();
            $table->string('celular',10)->nullable();
            $table->string('photo')->nullable();

            $table->date('f_ini');
            $table->date('f_fin');
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
        Schema::dropIfExists('asesor_externos');
    }
}
