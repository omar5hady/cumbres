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
                //Moral arrendatario
            $table->string('representante_arrendatario',60)->nullable();
            $table->string('dir_arrendatario',80)->nullable();
            $table->string('cp_arrendatario',5)->nullable();
            $table->string('col_arrendatario',40)->nullable();
            $table->string('estado_arrendatario',40)->nullable();
            $table->string('municipio_arrendatario',40)->nullable();

            //Aval (Fiador)
            $table->boolean('tipo_aval')->default(0);
            $table->string('nombre_aval')->nullable();
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
