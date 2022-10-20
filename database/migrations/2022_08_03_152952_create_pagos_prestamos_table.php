<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosPrestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_prestamos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('solic_id');
            $table->float('monto_pago',10,2);
            $table->date('fecha_pago')->nullable();
            $table->date('fecha_quincena')->nullable();
            $table->string('concepto',30)->nullable();
            $table->boolean('status');
            $table->float('monto_pago_extra',10,2)->default(0);
            $table->float('saldo',10,2)->default(0);

            $table->foreign('solic_id')->references('id')->on('prestamos_personales')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos_prestamos');
    }
}
