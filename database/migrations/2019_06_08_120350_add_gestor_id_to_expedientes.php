<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGestorIdToExpedientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expedientes', function($table) {
            $table->unsignedInteger('gestor_id')->default(1);
            $table->date('fecha_integracion')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->double('valor_escrituras')->default(0)->nullable();
            $table->date('fecha_infonavit')->nullable();

            $table->double('descuento')->default(0)->nullable();
            $table->double('total_liquidar')->default(0)->nullable();
            $table->date('fecha_liquidacion')->nullable();

            $table->double('infonavit')->default(0)->nullable();
            $table->double('fovissste')->default(0)->nullable();

            $table->boolean('liquidado')->default(0)->nullable();
            $table->boolean('postventa')->default(0)->nullable();

            $table->double('interes_ord')->default(0)->nullable();

            $table->string('doc_escrituras')->nullable();
            $table->date('doc_date')->nullable();

            $table->foreign('gestor_id')->references('id')->on('personal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
