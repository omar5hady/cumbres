<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 30)->unique();
            $table->string('descripcion', 100)->nullable();
            $table->boolean('condicion')->default(1);          
        });
        DB::table('roles')->insert(array('id'=>'1','nombre'=>'Administrador', 'descripcion'=>'Administradores de area'));
        DB::table('roles')->insert(array('id'=>'2','nombre'=>'Asesor', 'descripcion'=>'Vendedor área venta'));
        DB::table('roles')->insert(array('id'=>'3','nombre'=>'Gerente Proyectos', 'descripcion'=>'Gerente de area de proyectos'));
        DB::table('roles')->insert(array('id'=>'4','nombre'=>'Gerente ventas', 'descripcion'=>'Gerente de area de ventas'));
        DB::table('roles')->insert(array('id'=>'5','nombre'=>'Gerente obra', 'descripcion'=>'Gerente de area de obra y construccion'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
