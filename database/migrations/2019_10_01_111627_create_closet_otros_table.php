<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosetOtrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closet_otros', function (Blueprint $table) {
            $table->unsignedInteger('id');
            //Paredes daños
            $table->boolean('pared_dan_der')->default(1)->nullable();
            $table->boolean('pared_dan_izq')->default(1)->nullable();
            $table->boolean('pared_dan_princ')->default(1)->nullable();
            $table->boolean('pared_dan_baja')->default(1)->nullable();
            //Paredes limpieza
            $table->boolean('pared_limp_der')->default(1)->nullable();
            $table->boolean('pared_limp_izq')->default(1)->nullable();
            $table->boolean('pared_limp_princ')->default(1)->nullable();
            $table->boolean('pared_limp_baja')->default(1)->nullable();
            //Closet Cenefa sup
            $table->boolean('clo_censup_der')->default(1)->nullable();
            $table->boolean('clo_censup_izq')->default(1)->nullable();
            $table->boolean('clo_censup_princ')->default(1)->nullable();
            $table->boolean('clo_censup_baja')->default(1)->nullable();
            //Closet Cenefa inf
            $table->boolean('clo_ceninf_der')->default(1)->nullable();
            $table->boolean('clo_ceninf_izq')->default(1)->nullable();
            $table->boolean('clo_ceninf_princ')->default(1)->nullable();
            $table->boolean('clo_ceninf_baja')->default(1)->nullable();
            //Closet color madera
            $table->boolean('clo_madera_der')->default(1)->nullable();
            $table->boolean('clo_madera_izq')->default(1)->nullable();
            $table->boolean('clo_madera_princ')->default(1)->nullable();
            $table->boolean('clo_madera_baja')->default(1)->nullable();
            //Closet Alinec jalad
            $table->boolean('clo_alin_der')->default(1)->nullable();
            $table->boolean('clo_alin_izq')->default(1)->nullable();
            $table->boolean('clo_alin_princ')->default(1)->nullable();
            $table->boolean('clo_alin_baja')->default(1)->nullable();
            //Closet pandeadura
            $table->boolean('clo_pande_der')->default(1)->nullable();
            $table->boolean('clo_pande_izq')->default(1)->nullable();
            $table->boolean('clo_pande_princ')->default(1)->nullable();
            $table->boolean('clo_pande_baja')->default(1)->nullable();
            //Closet soporte
            $table->boolean('clo_soporte_der')->default(1)->nullable();
            $table->boolean('clo_soporte_izq')->default(1)->nullable();
            $table->boolean('clo_soporte_princ')->default(1)->nullable();
            $table->boolean('clo_soporte_baja')->default(1)->nullable();

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
        Schema::dropIfExists('closet_otros');
    }
}
