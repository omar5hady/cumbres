<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('personal')->onDelete('cascade');
            $table->integer('supervisor_id')->unsigned()->nullable();
            $table->foreign('supervisor_id')->references('id')->on('personal');
            $table->string('inmobiliaria',50)->nullable();
            $table->boolean('tipo')->default(0);
            $table->integer('esquema')->default(2);
            $table->date('fecha_sueldo')->nullable();
            $table->string('doc_ine')->nullable();
            $table->string('doc_comprobante')->nullable();
            $table->string('curriculum')->nullable();

            $table->boolean('isr')->default(0);
            $table->boolean('retencion')->default(0);
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
        Schema::dropIfExists('vendedores');
    }
}
