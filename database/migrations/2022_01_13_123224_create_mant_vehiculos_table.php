<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMantVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mant_vehiculos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('solicitante',50);
            $table->integer('vehiculo');
            $table->string('reparacion',80);
            $table->string('descripcion')->nullable();
            $table->string('taller',50);
            $table->boolean('forma_pago')->default(0); //0 Efectivo, 1 Tarjeta Debito, 2 Tarjeta Crédito
            $table->double('importe_total',10,2)->default(0);
            $table->double('monto_comp',10,2)->default(0);
            $table->double('monto_gcc',10,2)->default(0);
            $table->boolean('status')->default(1); //0 Cancelado, 1 Pendiente, 2 Aprobado, 3 Liquidado.

            $table->date('recep_jefe')->nullable();
            $table->date('recep_rh')->nullable();
            $table->date('recep_control')->nullable();
            $table->date('recep_direccion')->nullable();

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
        Schema::dropIfExists('mant_vehiculos');
    }
}
