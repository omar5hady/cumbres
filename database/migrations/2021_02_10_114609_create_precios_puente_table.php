<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreciosPuenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios_puente', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solicitud_id');
            $table->integer('modelo_id'); 
            $table->double('precio')->default(0);
            $table->double('precio_c')->default(0);
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
        Schema::dropIfExists('precios_puente');
    }
}
