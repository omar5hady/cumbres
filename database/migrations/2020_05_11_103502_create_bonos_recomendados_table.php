<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonosRecomendadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonos_recomendados', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->foreign('id')->references('id')->on('contratos');
            $table->date('fecha_aut1')->nullable();
            $table->date('fecha_aut2')->nullable();
            $table->double('monto')->default(10000.00);
            $table->string('descripcion')->nullable();
            $table->date('ini_promo')->nullable();
            $table->date('fin_promo')->nullable();
            $table->string('recomendado');
            $table->date('fecha_pago')->nullable();
            $table->boolean('status')->default(0);
            
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
        Schema::dropIfExists('bonos_recomendados');
    }
}
