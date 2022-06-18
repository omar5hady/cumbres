<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->unsignedInteger('id');////////////
            $table->char('sexo',1);
            $table->string('tipo_casa',25);
            $table->string('email_institucional',40)->nullable();
            $table->string('lugar_contacto',40)->nullable();
            $table->unsignedInteger('proyecto_interes_id');/////////////
            $table->unsignedInteger('publicidad_id');//////////////////
            $table->boolean('edo_civil'); //TinyInt
            $table->string('nss',11)->nullable();
            $table->string('curp',18)->nullable();
            $table->unsignedInteger('vendedor_id')->nullable();////////////////
            $table->string('empresa')->nullable();
            $table->boolean('precio_rango')->default(0);
            $table->double('ingreso')->nullable();
            $table->boolean('coacreditado')->default(0); //TinyInt
            $table->boolean('clasificacion')->default(1);
            $table->string('lugar_nacimiento',80)->nullable();
            $table->string('estado',80)->nullable();
            $table->string('ciudad',80)->nullable();
            $table->string('puesto',25)->nullable();
            $table->boolean('nacionalidad')->default(0)->nullable();
            $table->string('nombre_recomendado',80)->nullable();


            $table->char('sexo_coa',1)->nullable();
            $table->string('tipo_casa_coa',25)->nullable();
            $table->string('email_institucional_coa',40)->nullable();
            $table->string('empresa_coa')->nullable();
            $table->boolean('edo_civil_coa')->nullable(); //TinyInt
            $table->string('nss_coa',11)->nullable();
            $table->string('curp_coa',18)->nullable();
            $table->string('nombre_coa',35)->nullable();
            $table->string('apellidos_coa',70)->nullable();
            $table->date('f_nacimiento_coa')->nullable();
            $table->boolean('nacionalidad_coa')->nullable();
            $table->string('lugar_nacimiento_coa',80)->nullable();
            $table->string('rfc_coa',10)->nullable();
            $table->string('homoclave_coa',3)->nullable();
            $table->string('direccion_coa',80)->nullable();
            $table->string('colonia_coa',80)->nullable();
            $table->string('ciudad_coa',80)->nullable();
            $table->string('estado_coa',80)->nullable();
            $table->integer('cp_coa')->nullable();
            $table->string('telefono_coa',10)->nullable();
            $table->string('ext_coa',5)->nullable();
            $table->string('celular_coa',10)->nullable();
            $table->string('email_coa',40)->nullable();
            $table->string('parentesco_coa',20)->nullable();

            $table->integer('user_alta')->nullable();
            $table->integer('ultimo_vendedor')->nullable();
            $table->integer('penultimo_vendedor')->nullable();
            $table->integer('contador')->default(0);

            $table->integer('vendedor_aux')->nullable();
            $table->boolean('reasignar')->default(0);
            $table->boolean('app_alta')->default(0);

            $table->date('seguimiento')->nullable();

            $table->timestamps();

            $table->foreign('id')->references('id')->on('personal')->onDelete('cascade');
            $table->foreign('proyecto_interes_id')->references('id')->on('fraccionamientos')->onDelete('cascade');
            $table->foreign('publicidad_id')->references('id')->on('medios_publicitarios')->onDelete('cascade');
            $table->foreign('vendedor_id')->references('id')->on('vendedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
