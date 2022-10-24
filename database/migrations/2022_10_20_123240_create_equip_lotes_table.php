<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equip_lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lote_id');
            $table->date('fecha_solicitud')->nullable();
            $table->double('costo')->nullable()->default(0);
            $table->date('fecha_colocacion')->nullable();
            $table->double('anticipo')->nullable()->default(0);
            $table->date('fecha_anticipo')->nullable();
            $table->unsignedInteger('equipamiento_id');

            $table->double('liquidacion')->nullable()->default(0);
            $table->date('fecha_liquidacion')->nullable();
            $table->date('fin_instalacion')->nullable();
            $table->string('num_factura', 8)->nullable();

            $table->boolean('status')->default(1);
            $table->boolean('control')->default(0);
            $table->boolean('recepcion')->default(0);

            $table->boolean('anticipo_cand')->default(0);
            $table->boolean('liquidacion_cand')->default(0);
            $table->string('comp_pago_1',95)->nullable();
            $table->string('comp_pago_2',95)->nullable();

            $table->timestamps();
            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('equipamiento_id')->references('id')->on('equipamientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equip_lotes');
    }
}
