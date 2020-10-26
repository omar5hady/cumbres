<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDigitalLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('medio_contacto',80);
            $table->string('medio_publicidad')->nullable();
            $table->unsignedInteger('campania_id')->nullable();
            $table->foreign('campania_id')->references('id')->on('campanias')->onDelete('cascade');
            $table->string('nombre',50);
            $table->string('apellidos',80)->nullable();
            $table->string('email')->nullable();
            $table->string('celular',10)->nullable();
            $table->string('telefono',10)->nullable();
            $table->integer('proyecto_interes')->nullable();
            $table->string('modelo_interes')->nullable();
            $table->double('rango1')->nullable();
            $table->double('rango2')->nullable();
            $table->boolean('edo_civil')->nullable(); //TinyInt
            $table->string('perfil_cliente')->nullable(); 
            $table->double('ingresos')->default(0);
            $table->boolean('coacreditado')->nullable();
            $table->boolean('hijos')->nullable();
            $table->integer('num_hijos')->default(0);
            $table->boolean('mascotas')->nullable();
            $table->boolean('tam_mascota')->default(0);
            $table->string('tipo_credito')->nullable();
            $table->boolean('tipo_uso')->nullable();
            $table->string('empresa')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('vendedor_asign')->nullable();

            $table->string('rfc',10)->nullable();
            $table->string('nss',11)->nullable();
            $table->char('sexo',1)->nullable();
            $table->date('f_nacimiento')->nullable();
        
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
        Schema::dropIfExists('digital_leads');
    }
}
