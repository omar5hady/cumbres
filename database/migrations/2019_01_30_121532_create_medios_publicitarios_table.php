<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediosPublicitariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medios_publicitarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
        });
        DB::table('medios_publicitarios')->insert(array('id'=>'1','nombre'=>'Recomendado'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medios_publicitarios');
    }
}
