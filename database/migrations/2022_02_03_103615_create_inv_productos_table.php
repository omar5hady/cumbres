<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_productos', function (Blueprint $table) {
            //Tabla para registrar entradas de productos
            $table->increments('id');
            $table->date('fecha');
            $table->string('concepto',60);
            $table->integer('proveedor');
            $table->integer('num_factura')->nullable();
            $table->integer('cantidad')->default(0);
            $table->string('unidad')->nullable();
            $table->float('p_unit')->default(0);
            $table->float('total')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('tipo_producto');
            $table->integer('user_id');
            $table->boolean('tipo_mov')->default(0);
            $table->string('oficina',60)->default('Compra');
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
        Schema::dropIfExists('inv_productos');
    }
}
