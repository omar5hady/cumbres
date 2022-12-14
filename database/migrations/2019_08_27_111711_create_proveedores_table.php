<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('proveedor',80);
            $table->string('contacto',70)->nullable();
            $table->string('direccion',80)->nullable();
            $table->string('colonia',50)->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('email',70)->nullable();
            $table->string('email2',70)->nullable();
            $table->string('poliza')->nullable();
            $table->string('num_cuenta',50)->nullable();
            $table->string('clabe',50)->nullable();
            $table->string('banco',80)->nullable();
            $table->boolean('tipo')->default(0);
            $table->string('const_fisc',255)->nullable();
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
        Schema::dropIfExists('proveedores');
    }
}
