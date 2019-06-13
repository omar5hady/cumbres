<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddObservacionToAvaluos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaluos', function($table) {
            $table->date('fecha_ava_sol')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('status')->default('Pendiente')->nullable();
            $table->date('fecha_concluido')->nullable();
            $table->double('costo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
