<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpSolicitudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Creating a table called sp_solicituds */
        Schema::create('sp_solicituds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('empresa_solic');
            $table->integer('solicitante_id');
            $table->string('departamento',100);
            $table->integer('proveedor_id');
            $table->double('importe')->default(0);
            $table->boolean('tipo_pago')->default(0);  //? 0:CF, 1:B
            $table->boolean('forma_pago')->nullable();//? 0:Transferencia, 1:Cheque
            $table->boolean('caja_chica')->default(0)->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('vb_gerente')->default(0);
            $table->boolean('vb_direccion')->default(0);
            $table->boolean('vb_tesoreria')->default(0);
            $table->boolean('autorizar')->default(0);
            $table->date('fecha_compra')->nullable();
            $table->string('banco',80)->nullable();
            $table->string('num_cuenta',50)->nullable();
            $table->string('clabe',50)->nullable();
            $table->string('num_factura')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->double('monto_aut')->default(0);
            $table->boolean('rechazado')->default(0);

            $table->date('entrega_pago')->nullable();
            $table->string('comprobante_pago')->nullable();

            $table->string('cuenta_pago')->nullable();
            $table->boolean('beneficiario')->default(0);//? Abono a cuenta de beneficiario? 0: NO, 1:Si

            $table->boolean('extraordinario')->default(0);//?  0: NO, 1:Si

            $table->boolean('rev_op')->default(0);

            $table->string('convenio')->nullable();
            $table->string('referencia')->nullable();
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
        Schema::dropIfExists('sp_solicituds');
    }
}
