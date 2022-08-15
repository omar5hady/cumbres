<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestamosPersonalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamos_personales', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->integer('jefe_id');
            $table->float('monto_solicitado',10,2);
            $table->date('fecha_ini');
            
            $table->date('fecha_status_rh')->nullable();
            $table->string('motivo',50)->nullable();
            $table->boolean('rh_band')->default(0);
            $table->boolean('jefe_band')->default(0);
            $table->boolean('dir_band')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('status_rh')->default(1);
            $table->float('desc_quin',10,2);
            $table->float('saldo',10,2);
            
           
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestamos_personales');
    }
}
