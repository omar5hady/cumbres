<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIniObraLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ini_obra_lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ini_obra_id');
            $table->unsignedInteger('lote_id')->nullable();
            $table->string('manzana')->nullable();
            $table->float('superficie')->nullable();
            $table->double('costo_directo');
            $table->double('costo_indirecto');
            $table->double('importe');
            $table->text('descripcion');
            $table->boolean('iniciado')->default(0);
            $table->timestamps();

            $table->foreign('ini_obra_id')->references('id')->on('ini_obras');
            $table->foreign('lote_id')->references('id')->on('lotes');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ini_obra_lotes');
    }
}
