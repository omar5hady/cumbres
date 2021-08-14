<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartidasUrbanizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidas_urbanizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partida',50);
            $table->timestamps();
        });
        DB::table('partidas_urbanizacion')->insert(array('id'=>'1','partida'=>'Terracerias Vialidad'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'2','partida'=>'Descargas Domiciliarias'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'3','partida'=>'Drenaje'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'4','partida'=>'Agua Potable'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'5','partida'=>'Alumbrado'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'6','partida'=>'Electrificación'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'7','partida'=>'Guarniciones'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'8','partida'=>'Banquetas'));
        DB::table('partidas_urbanizacion')->insert(array('id'=>'9','partida'=>'Pavimento'));
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partidas_urbanizacion');
    }
}
