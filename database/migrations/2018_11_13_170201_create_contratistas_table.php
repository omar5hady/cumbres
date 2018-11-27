<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratistas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->boolean('tipo')->default(1);
            $table->string('rfc',13)->unique();
            $table->string('direccion',80);
            $table->string('colonia',80)->nullable();
            $table->integer('cp')->nullable();
            $table->string('estado',100)->nullable();
            $table->string('ciudad',100)->nullable();
            $table->string('representante',100)->nullable();
            $table->string('IMSS',30)->nullable();
            $table->string('telefono',20);
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
        Schema::dropIfExists('contratistas');
    }
}
