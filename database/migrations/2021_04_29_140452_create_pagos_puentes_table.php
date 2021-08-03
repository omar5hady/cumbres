<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosPuentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_puentes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('credito_puente_id');
            $table->date('fecha');
            $table->boolean('tipo')->default(0);
            $table->string('concepto');
            $table->double('abono')->default(0);
            $table->double('cargo')->default(0);
            $table->double('comisiones')->default(0);
            $table->double('honorarios')->default(0);
            $table->integer('deposito_id')->nullable();
            $table->float('porc_interes')->default(0);
            $table->date('fecha_interes')->nullable();
            $table->double('monto_interes')->default(0);
            $table->boolean('pendiente')->default(0);
            $table->double('saldo')->default(0);
            // $table->integer('mes')->default(0);
            // $table->double('anio')->default(0);

            $table->string('doc_pago')->nullable();
            $table->string('doc_interes')->nullable();
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
        Schema::dropIfExists('pagos_puentes');
    }
}
