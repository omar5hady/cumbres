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

            $table->string('e_factura')->nullable(); //factura de firma de escrituras
            $table->string('e_folio_factura',30)->nullable(); //factura de firma de escrituras
            $table->double('e_monto', 8,2)->nullable()->default(0); //factura de firma de escrituras
            $table->date('e_f_carga_factura')->nullable(); //factura de firma de escrituras

            $table->string('e_factura_concretania')->nullable();
            $table->string('e_folio_factura_concretania',30)->nullable();
            $table->double('e_monto_concretania', 8,2)->nullable()->default(0);
            $table->date('e_f_carga_factura_concretania')->nullable();
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
