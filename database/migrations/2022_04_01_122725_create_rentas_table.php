<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lote_id')->nullable();

            //Arrendatario
            $table->boolean('tipo_arrendatario')->default(0);
            $table->string('nombre_arrendatario',80);
            $table->string('tel_arrendatario',10);
            $table->integer('clv_lada_arr',4)->default(52);
                //Moral arrendatario
            $table->string('representante_arrendatario',60)->nullable();
            $table->string('dir_arrendatario',80)->nullable();
            $table->string('cp_arrendatario',5)->nullable();
            $table->string('col_arrendatario',40)->nullable();
            $table->string('estado_arrendatario',40)->nullable();
            $table->string('municipio_arrendatario',40)->nullable();
            $table->string('rfc_arrendatario',13)->nullable();

            //Aval (Fiador)
            $table->boolean('tipo_aval')->default(0);
            $table->string('nombre_aval')->nullable();
            $table->string('tel_aval',10);
            $table->integer('clv_lada_aval',4)->default(52);
                //Moral aval
            $table->string('representante_aval')->nullable();
            $table->string('dir_aval')->nullable();
            $table->string('cp_aval',5)->nullable();
            $table->string('col_aval',40)->nullable();
            $table->string('estado_aval',40)->nullable();
            $table->string('municipio_aval',40)->nullable();

            //Testigo
            $table->string('nombre')->nullable();

            //Datos contrato
            $table->float('monto_renta')->defaut(0);
            $table->boolean('status',1)->default(1);
            $table->date('fecha_firma')->nullable();
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('num_meses')->default(0);

            $table->float('dep_garantia',10,2)->default(0);

            //Servicios y muebles
            $table->boolean('servicios')->default(0);
            $table->boolean('muebles')->default(0);
            $table->boolean('adendum')->default(0);
            $table->string('archivo_contrato')->nullable();

            $table->float('luz',8,2)->default(0);
            $table->float('agua',8,2)->default(0);
            $table->float('gas',8,2)->default(0);
            $table->float('television',8,2)->default(0);
            $table->float('telefonia',8,2)->default(0);

            //Datos fiscales
            $table->boolean('facturar')->default(0);
            $table->string('email_fisc',60)->nullable();
            $table->string('tel_fisc',10)->nullable();
            $table->string('nombre_fisc',120)->nullable();
            $table->string('direccion_fisc',100)->nullable();
            $table->string('col_fisc',40)->nullable();
            $table->string('cp_fisc',5)->nullable();
            $table->string('rfc_fisc',13)->nullable();
            $table->string('cfi_fisc',50)->nullable();
            $table->string('regimen_fisc',50)->nullable();
            $table->string('banco_fisc',50)->nullable();
            $table->string('num_cuenta_fisc',50)->nullable();
            $table->string('clabe_fisc',50)->nullable();

            $table->timestamps();

            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentas');
    }
}
