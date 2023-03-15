<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('prospecto_id')->nullable();
            $table->integer('num_dep_economicos')->nullable();
            $table->string('tipo_economia')->nullable();
            $table->string('nombre_primera_ref')->nullable();
            $table->string('telefono_primera_ref')->nullable();
            $table->string('celular_primera_ref')->nullable();
            $table->string('nombre_segunda_ref')->nullable();
            $table->string('telefono_segunda_ref')->nullable();
            $table->string('celular_segunda_ref')->nullable();
            $table->string('fraccionamiento')->nullable();
            $table->string('etapa')->nullable();
            $table->string('manzana')->nullable();
            $table->string('num_lote')->nullable();
            $table->string('modelo')->nullable();
            $table->double('precio_base')->nullable();
            $table->float('superficie')->nullable();
            $table->float('terreno_excedente')->nullable();
            $table->double('precio_terreno_excedente')->nullable();
            $table->double('precio_obra_extra')->nullable();
            $table->string('promocion')->nullable();
            $table->text('descripcion_promocion')->nullable();
            $table->double('descuento_promocion')->nullable();
            $table->string('paquete')->nullable();
            $table->text('descripcion_paquete')->nullable();
            $table->text('desc_eq_paquete')->nullable();
            $table->text('desc_eq_promo')->nullable();
            $table->double('costo_paquete')->nullable();
            $table->double('precio_venta')->nullable();
            $table->integer('plazo')->nullable();
            $table->double('credito_solic')->nullable();
            $table->boolean('status')->default(1)->nullable();
            $table->unsignedInteger('lote_id')->nullable();
            $table->boolean('contrato')->default(0)->nullable();
            $table->boolean('bono')->default(0)->nullable();
            $table->unsignedInteger('vendedor_id')->nullable();


            $table->integer('descuento_id')->nullable();
            $table->string('descuento_desc')->nullable();
            $table->double('descuento_cant')->default(0);

            $table->double('costo_descuento')->default(0);
            $table->double('descuento_terreno')->default(0);
            $table->double('descuento_ubicacion')->default(0);
            $table->double('costo_alarma')->default(0);
            $table->double('costo_cuota_mant')->default(0);
            $table->double('costo_protecciones')->default(0);

            $table->timestamps();

            $table->foreign('prospecto_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('cascade');
            $table->foreign('vendedor_id')->references('id')->on('vendedores')->onDelete('cascade');

            $table->string('factura')->nullable();
            $table->string('folio_factura',30)->nullable();
            $table->double('monto', 10,2)->nullable()->default(0);
            $table->date('f_carga_factura')->nullable();

            $table->double('valor_terreno',10,2)->default(0);
            $table->double('saldo_terreno',10,2)->default(0);
            $table->float('porcentaje_terreno',6,2)->default(0);
            $table->boolean('dev_terreno')->default(0);

            //Datos fiscales
            $table->string('email_fisc',60)->nullable();
            $table->string('tel_fisc',10)->nullable();
            $table->string('nombre_fisc',120)->nullable();
            $table->string('direccion_fisc',100)->nullable();
            $table->string('col_fisc',40)->nullable();
            $table->string('cp_fisc',5)->nullable();
            $table->string('rfc_fisc',13)->nullable();
            $table->string('cfi_fisc',50)->nullable();
            $table->string('regimen_fisc',50)->nullable();
            $table->string('banco_fisc',50)->nullable();
            $table->string('num_cuenta_fisc',50)->nullable();
            $table->string('clabe_fisc',50)->nullable();
            $table->string('archivo_fisc',100)->nullable();
            $table->date('fecha_rfc')->nullable();
            $table->date('fecha_archivo')->nullable();

            $table->boolean('notif_fisc')->default(0);
            $table->boolean('integracion_cobro')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditos');
    }
}

