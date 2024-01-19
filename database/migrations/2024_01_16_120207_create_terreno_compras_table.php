<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerrenoComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terreno_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100);
            $table->string('direccion',150)->nullable();
            $table->double('valor_venta')->default(0);
            $table->double('valor_compra')->default(0);
            $table->double('valor_escritura')->default(0);
            $table->date('fecha_firma_promesa')->nullable();
            $table->date('fecha_firma_esc')->nullable();
            $table->float('tamanio')->default(0);
            $table->string('comprador', 100)->nullable();
            $table->string('vendedor', 100)->nullable();
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
        Schema::dropIfExists('terreno_compras');
    }
}
