<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('personal')->onDelete('cascade');
             
            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);
            $table->string('foto_user')->default('default-image.gif');
            $table->integer('rol_id')->unsigned();
            $table->foreign('rol_id')->references('id')->on('roles');

            //Menus principales
            $table->boolean('administracion')->default(0);
            $table->boolean('desarrollo')->default(0);
            $table->boolean('precios')->default(0);
            $table->boolean('obra')->default(0);
            $table->boolean('ventas')->default(0);
            $table->boolean('acceso')->default(0);
            $table->boolean('reportes')->default(0);


            //Administracion
            $table->boolean('personas')->default(0);
            $table->boolean('empresas')->default(0);
            $table->boolean('medios_public')->default(0);
            $table->boolean('departamentos')->default(0);
            $table->boolean('lugares_contacto')->default(0);
            $table->boolean('servicios')->default(0);
            $table->boolean('inst_financiamiento')->default(0);
            $table->boolean('tipos_credito')->default(0);
            $table->boolean('asig_servicios')->default(0);
            $table->boolean('mis_asesores')->default(0);
            $table->boolean('cuenta')->default(0);


            //Desarrollo
            $table->boolean('fraccionamiento')->default(0);
            $table->boolean('etapas')->default(0);
            $table->boolean('modelos')->default(0);
            $table->boolean('lotes')->default(0);
            $table->boolean('asign_modelos')->default(0);
            $table->boolean('licencias')->default(0);
            $table->boolean('acta_terminacion')->default(0);
            $table->boolean('p_fraccionamiento')->default(0);
            $table->boolean('p_etapa')->default(0);

            //Precios
            $table->boolean('agregar_sobreprecios')->default(0);
            $table->boolean('precios_etapas')->default(0);
            $table->boolean('precios_viviendas')->default(0);
            $table->boolean('sobreprecios')->default(0);
            $table->boolean('paquetes')->default(0);
            $table->boolean('promociones')->default(0);

            //Obra
            $table->boolean('contratistas')->default(0);
            $table->boolean('ini_obra')->default(0);
            $table->boolean('aviso_obra')->default(0);
            $table->boolean('partidas')->default(0);
            $table->boolean('avance')->default(0);

            //Ventas
            $table->boolean('lotes_disp')->default(0);
            $table->boolean('mis_prospectos')->default(0);
            $table->boolean('simulacion_credito')->default(0);
            $table->boolean('hist_simulaciones')->default(0);
            $table->boolean('hist_creditos')->default(0);
            $table->boolean('contratos')->default(0);

            //Acceso
            $table->boolean('usuarios')->default(0);
            $table->boolean('roles')->default(0);

            //Reportes
            $table->boolean('mejora')->default(0);
            

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
