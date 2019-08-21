<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirmaEscriturasToExpedientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expedientes', function($table) {
            $table->date('fecha_firma_esc')->nullable();
            $table->integer('notaria_id')->nullable();
            $table->string('notaria')->nullable();
            $table->string('notario')->nullable();
            $table->time('hora_firma')->nullable();

            $table->string('direccion_firma')->nullable();

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
