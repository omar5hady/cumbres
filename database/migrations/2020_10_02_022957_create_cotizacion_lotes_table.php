<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion_lotes', function (Blueprint $table) {
            
            $table->increments('id');

            $table->unsignedInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes');

            $table->unsignedInteger('lotes_id')->nullable();
            $table->foreign('lotes_id')->references('id')->on('lotes');

            $table->double('valor_venta')->default(0);
            $table->double('valor_descuento')->default(0);
            $table->date('fecha');
            $table->integer('mensualidades')->default(0);
            $table->boolean('estatus')->default(0);

            $table->integer('num_contrato')->nullable();
            
        });
    }

    //ALTER TABLE `db_cumbres`.`cotizacion_lotes` 
    //ADD COLUMN `mensualidades` INT(10) NOT NULL DEFAULT '0' AFTER `fecha`;
    //ALTER TABLE `db_cumbres`.`cotizacion_lotes` 
    //ADD COLUMN `estatus` INT(1) NOT NULL DEFAULT '0' AFTER `mensualidades`;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizacion_lotes');
    }
}
