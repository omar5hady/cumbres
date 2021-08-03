<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('departamento_id');
            $table->string('apellidos',70);
            $table->string('nombre',35);
            $table->date('f_nacimiento');
            $table->string('rfc',13)->unique();
            $table->string('homoclave',3)->nullable();
            $table->string('direccion',80)->nullable();
            $table->string('colonia',80)->nullable();
            $table->integer('cp')->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('ext',5)->nullable();
            $table->string('celular',10);
            $table->string('email',40);
            $table->boolean('activo')->default(1);       
            $table->integer('clv_lada',4)->default(52);
            $table->timestamps();

            $table->foreign('departamento_id')->references('id_departamento')->on('departamento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal');
    }
}
