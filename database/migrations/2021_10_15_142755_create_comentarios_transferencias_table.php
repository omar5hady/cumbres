<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosTransferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios_transferencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('deposito_id')->nullable();
            $table->integer('dep_conc')->nullable();
            $table->integer('dep_gcc')->nullable();
            $table->string('comentario');
            $table->string('usuario',80);
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
        Schema::dropIfExists('comentarios_transferencias');
    }
}
