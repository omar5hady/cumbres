<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLugaresContactoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lugares_contacto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',60);
            $table->string('usuario',40);
            $table->timestamps();
        });
        DB::table('lugares_contacto')->insert(array('id'=>'1','nombre'=>'Oficina central', 'usuario'=>'Administrador'));
        DB::table('lugares_contacto')->insert(array('id'=>'2','nombre'=>'Escuela de PFP', 'usuario'=>'Administrador'));
        DB::table('lugares_contacto')->insert(array('id'=>'3','nombre'=>'Feria Expo TUCASA', 'usuario'=>'Administrador'));
        DB::table('lugares_contacto')->insert(array('id'=>'4','nombre'=>'Pagina Web', 'usuario'=>'Administrador'));
        DB::table('lugares_contacto')->insert(array('id'=>'5','nombre'=>'Modulo', 'usuario'=>'Administrador'));
        DB::table('lugares_contacto')->insert(array('id'=>'6','nombre'=>'Feria de Vivienda', 'usuario'=>'Administrador'));
        DB::table('lugares_contacto')->insert(array('id'=>'7','nombre'=>'El Dorado', 'usuario'=>'Administrador'));
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lugares_contacto');
    }
}
