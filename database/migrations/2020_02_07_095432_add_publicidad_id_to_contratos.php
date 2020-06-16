<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublicidadIdToContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function($table) {
            $table->unsignedInteger('publicidad_id')->nullable();
            $table->foreign('publicidad_id')->references('id')->on('medios_publicitarios');

            $table->string('c_factura')->nullable(); //factura de contrato
            $table->string('c_folio_factura',30)->nullable(); //factura de contrato
            $table->double('c_monto', 8,2)->nullable(); //factura de contrato
            $table->date('c_f_carga_factura')->nullable(); //factura de contrato

            $table->string('e_factura')->nullable(); //factura de firma de escrituras
            $table->string('e_folio_factura',30)->nullable(); //factura de firma de escrituras
            $table->double('e_monto', 8,2)->nullable(); //factura de firma de escrituras
            $table->date('e_f_carga_factura')->nullable(); //factura de firma de escrituras
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
