<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstSeleccionadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inst_seleccionadas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('credito_id')->nullable();
            $table->string('tipo_credito')->nullable();
            $table->string('institucion')->nullable();
            $table->boolean('elegido')->default(0)->nullable();
            $table->timestamps();

            
            $table->foreign('credito_id')->references('id')->on('creditos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inst_seleccionadas');
    }
}
