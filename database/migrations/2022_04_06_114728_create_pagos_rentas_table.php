<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosRentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_rentas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('num_pago');
            $table->float('importe');
            $table->date('fecha');
            $table->unsignedInteger('renta_id')->nullable();
            $table->timestamps();

            $table->foreign('renta_id')->references('id')->on('rentas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos_rentas');
    }
}
