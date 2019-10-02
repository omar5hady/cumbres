<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosetAcabadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closet_acabados', function (Blueprint $table) {
            $table->unsignedInteger('id');
            //Puertas alineados
            $table->boolean('p_ali_der')->default(1)->nullable();
            $table->boolean('p_ali_izq')->default(1)->nullable();
            $table->boolean('p_ali_princ')->default(1)->nullable();
            $table->boolean('p_ali_baja')->default(1)->nullable();
            //Puertas limpieza
            $table->boolean('p_limp_der')->default(1)->nullable();
            $table->boolean('p_limp_izq')->default(1)->nullable();
            $table->boolean('p_limp_princ')->default(1)->nullable();
            $table->boolean('p_limp_baja')->default(1)->nullable();
            //Puertas silicon
            $table->boolean('p_sil_der')->default(1)->nullable();
            $table->boolean('p_sil_izq')->default(1)->nullable();
            $table->boolean('p_sil_princ')->default(1)->nullable();
            $table->boolean('p_sil_baja')->default(1)->nullable();
            //Cajones alineados
            $table->boolean('c_ali_der')->default(1)->nullable();
            $table->boolean('c_ali_izq')->default(1)->nullable();
            $table->boolean('c_ali_princ')->default(1)->nullable();
            $table->boolean('c_ali_baja')->default(1)->nullable();
            //Cajones cantos
            $table->boolean('c_cant_der')->default(1)->nullable();
            $table->boolean('c_cant_izq')->default(1)->nullable();
            $table->boolean('c_cant_princ')->default(1)->nullable();
            $table->boolean('c_cant_baja')->default(1)->nullable();
            //Cajones uniones
            $table->boolean('c_union_der')->default(1)->nullable();
            $table->boolean('c_union_izq')->default(1)->nullable();
            $table->boolean('c_union_princ')->default(1)->nullable();
            $table->boolean('c_union_baja')->default(1)->nullable();
            //Cajones silicon
            $table->boolean('c_sil_der')->default(1)->nullable();
            $table->boolean('c_sil_izq')->default(1)->nullable();
            $table->boolean('c_sil_princ')->default(1)->nullable();
            $table->boolean('c_sil_baja')->default(1)->nullable();
            //Cajones limpieza
            $table->boolean('c_limp_der')->default(1)->nullable();
            $table->boolean('c_limp_izq')->default(1)->nullable();
            $table->boolean('c_limp_princ')->default(1)->nullable();
            $table->boolean('c_limp_baja')->default(1)->nullable();
            //Cajones tornillos
            $table->boolean('c_torn_der')->default(1)->nullable();
            $table->boolean('c_torn_izq')->default(1)->nullable();
            $table->boolean('c_torn_princ')->default(1)->nullable();
            $table->boolean('c_torn_baja')->default(1)->nullable();
            //Cajones parches
            $table->boolean('c_parch_der')->default(1)->nullable();
            $table->boolean('c_parch_izq')->default(1)->nullable();
            $table->boolean('c_parch_princ')->default(1)->nullable();
            $table->boolean('c_parch_baja')->default(1)->nullable();

            $table->foreign('id')->references('id')->on('solic_equipamientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('closet_acabados');
    }
}
