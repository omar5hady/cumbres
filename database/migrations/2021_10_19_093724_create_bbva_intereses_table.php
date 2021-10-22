<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBbvaInteresesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbva_intereses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cPuente_id');
            $table->date('fecha_interes');
            $table->float('monto')->default(0);
            $table->double('saldo')->default(0);
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
        Schema::dropIfExists('bbva_intereses');
    }
}
