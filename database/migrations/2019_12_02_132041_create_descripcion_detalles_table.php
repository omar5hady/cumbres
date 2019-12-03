<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescripcionDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descripcion_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('solicitud_id');
            $table->unsignedInteger('detalle_id');
            $table->boolean('garantia');
            $table->string('observacion',100);
            $table->date('fecha_concluido')->nullable();
            $table->string('detalle');
            $table->string('subconcepto');
            $table->string('general');
            $table->double('costo')->default(0);
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id')->on('solic_detalles')->onDelete('cascade');
            $table->foreign('detalle_id')->references('id')->on('cat_detalles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('descripcion_detalles');
    }
}
