<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsPrestamosPersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_prestamos_pers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prestamo_id');
            $table->string('observacion');
            $table->string('usuario_id');
            $table->date('fecha_status_rh');
            $table->boolean('status_rh');
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
        Schema::dropIfExists('obs_prestamos_pers');
    }
}
