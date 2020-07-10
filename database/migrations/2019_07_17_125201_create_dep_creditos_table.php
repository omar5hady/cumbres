<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dep_creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inst_sel_id');
            $table->double('cant_depo')->default(0);
            $table->string('banco',50)->nullable();
            $table->string('concepto',80)->nullable();
            $table->date('fecha_deposito')->nullable();
            $table->timestamps();

            $table->foreign('inst_sel_id')->references('id')->on('inst_seleccionadas')->onDelete('cascade');

            $table->string('cuenta',50)->nullable();
            $table->date('fecha_ingreso_concretania')->nullable();

            $table->string('factura')->nullable();
            $table->string('folio_factura',30)->nullable();
            $table->double('monto', 8,2)->nullable()->default(0);
            $table->date('f_carga_factura')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dep_creditos');
    }
}
