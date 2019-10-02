<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosetInterioresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closet_interiores', function (Blueprint $table) {
            $table->unsignedInteger('id');
            //Puertas tiradores
            $table->boolean('p_tira_der')->default(1)->nullable();
            $table->boolean('p_tira_izq')->default(1)->nullable();
            $table->boolean('p_tira_princ')->default(1)->nullable();
            $table->boolean('p_tira_baja')->default(1)->nullable();
            //Puertas funcionamiento
            $table->boolean('p_func_der')->default(1)->nullable();
            $table->boolean('p_func_izq')->default(1)->nullable();
            $table->boolean('p_func_princ')->default(1)->nullable();
            $table->boolean('p_func_baja')->default(1)->nullable();
            //Cajones jaladeras
            $table->boolean('c_jalad_der')->default(1)->nullable();
            $table->boolean('c_jalad_izq')->default(1)->nullable();
            $table->boolean('c_jalad_princ')->default(1)->nullable();
            $table->boolean('c_jalad_baja')->default(1)->nullable();
            //Cajones rieles
            $table->boolean('c_riel_der')->default(1)->nullable();
            $table->boolean('c_riel_izq')->default(1)->nullable();
            $table->boolean('c_riel_princ')->default(1)->nullable();
            $table->boolean('c_riel_baja')->default(1)->nullable();
            //Cajones estantes
            $table->boolean('c_estant_der')->default(1)->nullable();
            $table->boolean('c_estant_izq')->default(1)->nullable();
            $table->boolean('c_estant_princ')->default(1)->nullable();
            $table->boolean('c_estant_baja')->default(1)->nullable();
            //Cajones entrepaños
            $table->boolean('c_entr_der')->default(1)->nullable();
            $table->boolean('c_entr_izq')->default(1)->nullable();
            $table->boolean('c_entr_princ')->default(1)->nullable();
            $table->boolean('c_entr_baja')->default(1)->nullable();
            //Cajones tubos colga
            $table->boolean('c_tubos_der')->default(1)->nullable();
            $table->boolean('c_tubos_izq')->default(1)->nullable();
            $table->boolean('c_tubos_princ')->default(1)->nullable();
            $table->boolean('c_tubos_baja')->default(1)->nullable();
            //Cajones daños
            $table->boolean('c_danos_der')->default(1)->nullable();
            $table->boolean('c_danos_izq')->default(1)->nullable();
            $table->boolean('c_danos_princ')->default(1)->nullable();
            $table->boolean('c_danos_baja')->default(1)->nullable();
            //Cajones abre correct
            $table->boolean('c_correct_der')->default(1)->nullable();
            $table->boolean('c_correct_izq')->default(1)->nullable();
            $table->boolean('c_correct_princ')->default(1)->nullable();
            $table->boolean('c_correct_baja')->default(1)->nullable();
            //Cajones pzas compl
            $table->boolean('c_pzasc_der')->default(1)->nullable();
            $table->boolean('c_pzasc_izq')->default(1)->nullable();
            $table->boolean('c_pzasc_princ')->default(1)->nullable();
            $table->boolean('c_pzasc_baja')->default(1)->nullable();
            //Cajones abatimiento
            $table->boolean('c_abatim_der')->default(1)->nullable();
            $table->boolean('c_abatim_izq')->default(1)->nullable();
            $table->boolean('c_abatim_princ')->default(1)->nullable();
            $table->boolean('c_abatim_baja')->default(1)->nullable();
            //Cajones visagras
            $table->boolean('c_visagras_der')->default(1)->nullable();
            $table->boolean('c_visagras_izq')->default(1)->nullable();
            $table->boolean('c_visagras_princ')->default(1)->nullable();
            $table->boolean('c_visagras_baja')->default(1)->nullable();

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
        Schema::dropIfExists('closet_interiores');
    }
}
