<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',80);
            $table->string('direccion',80);
            $table->integer('cp')->nullable();
            $table->string('colonia',80)->nullable();
            $table->string('estado',100)->nullable();
            $table->string('ciudad',100)->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('ext',5)->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('empresas');
    }
}
