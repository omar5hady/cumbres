<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasesPresupuestalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bases_presupuestales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('modelo_id');
            $table->boolean('activo')->default(1);
            $table->double('valor_venta');

            $table->integer('credito_id')->nullable();
            
            //// COMISIONES BANCARIAS :
                $table->double('insc_conjunto')->default(0);
                $table->double('int_nafin')->default(0);
                $table->double('gastos_esc')->default(0);
                $table->double('comision_int')->default(0);
                $table->double('int_cpuente')->default(0);
                $table->double('int_pago_terreno')->default(0);
            ///// BASES PRESUPUESTALES
                $table->double('valor_terreno')->default(0);
                $table->double('permisos')->default(0);
                $table->double('presupuesto_urb')->default(0);
                $table->double('presupuesto_edif')->default(0);
                $table->double('equipamiento')->default(0);
                $table->double('escritura_gcc')->default(0);
                $table->double('laboratorio')->default(0);
                $table->double('gastos_ind_op')->default(0);
                $table->double('gastos_comerc')->default(0);
                $table->double('comicion_venta')->default(0);
                $table->double('fianzas')->default(0);
                $table->double('partida_inflacionaria')->default(0);
                $table->double('adicional_terreno')->default(0);
            ///// INDICIES PRESUPUESTALES
                $table->double('ct_urbanizado')->default(0);
                $table->double('ct_brena')->default(0);
                $table->double('c_pavimentacion')->default(0);
                $table->double('c_edificacion1')->default(0);
                $table->double('c_edificacion2')->default(0);
                $table->double('precio_venta_const')->default(0);
                $table->double('c_adquisicion_brena')->default(0);

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
        Schema::dropIfExists('bases_presupuestales');
    }
}
