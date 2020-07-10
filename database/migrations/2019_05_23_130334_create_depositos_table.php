<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depositos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pago_id');
            $table->double('cant_depo')->default(0);
            $table->double('interes_mor')->default(0)->nullable();
            $table->double('interes_ord')->default(0)->nullable();
            $table->string('obs_mor')->nullable();
            $table->string('obs_ord')->nullable();
            $table->string('num_recibo',10)->nullable();
            $table->string('banco',50)->nullable();
            $table->string('concepto',80)->nullable();
            $table->date('fecha_pago')->nullable();

            $table->string('cuenta',50)->nullable();
            $table->date('fecha_ingreso_concretania')->nullable();

            $table->string('factura')->nullable();
            $table->string('folio_factura',30)->nullable();
            $table->double('monto', 8,2)->nullable()->default(0);
            $table->date('f_carga_factura')->nullable();

            $table->timestamps();

            $table->foreign('pago_id')->references('id')->on('pagos_contratos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depositos');
    }
}
