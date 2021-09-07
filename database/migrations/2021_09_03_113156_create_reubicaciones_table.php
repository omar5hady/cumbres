<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReubicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reubicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->integer('lote_id');
            $table->integer('cliente_id');
            $table->integer('asesor_id');
            $table->string('promocion')->nullable();
            $table->string('tipo_credito');
            $table->string('institucion');
            $table->double('valor_terreno',10,2)->default(0);
            $table->string('observacion')->nullable();
            $table->date('fecha_reubicacion');
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
        Schema::dropIfExists('reubicaciones');
    }
}
