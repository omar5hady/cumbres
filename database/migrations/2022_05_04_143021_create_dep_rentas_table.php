<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepRentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dep_rentas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renta_id');
            $table->float('monto_cap',10,2);
            $table->date('fecha_dep');
            $table->float('monto_int')->default(0);
            $table->string('num_recibo',10)->nullable();
            $table->string('banco',50)->nullable();
            $table->string('concepto',80)->nullable();
            $table->date('fecha_int')->nullable();
            $table->boolean('interes')->default(0);
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
        Schema::dropIfExists('dep_rentas');
    }
}
