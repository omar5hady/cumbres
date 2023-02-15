<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsesorCastigosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asesor_castigos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asesor_id');
            $table->date('f_ini');
            $table->date('f_fin');
            $table->integer('lead_id');
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
        Schema::dropIfExists('asesor_castigos');
    }
}
