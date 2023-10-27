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
            $table->string('lote')->default(0)->nullable();
            $table->string('manzana')->nullable();
            $table->string('modelo')->nullable();
            $table->string('construccion')->nullable();
            $table->double('costo_directo');
            $table->double('costo_indirecto');
            $table->double('obra_extra')->default(0)->nullable();
            $table->double('importe');
            $table->text('descripcion');
            $table->integer('lote_id')->nullable();

            $table->string('observacion')->nullable();
            $table->timestamps();

            $table->foreign('ini_obra_id')->references('id')->on('ini_obras')->onDelete('cascade');
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
