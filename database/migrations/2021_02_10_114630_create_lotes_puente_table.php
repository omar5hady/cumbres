<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotesPuenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes_puente', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solicitud_id');
            $table->integer('lote_id');
            $table->integer('modelo_id');
            $table->double('precio_p',10,2);

            $table->double('precio_c',10,2)->default(0);

            $table->string('modeloAnt1')->default('N/A');
            $table->double('precio1',10,2)->default(0.00);
            $table->string('modeloAnt2')->default('N/A');
            $table->double('precio2',10,2)->default(0.00);

            $table->double('saldo',10,2)->default(0);
            $table->double('abonado',10,2)->default(0);
            $table->double('cobrado',10,2)->default(0);
            $table->boolean('liberado')->default(0);
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
        Schema::dropIfExists('lotes_puente');
    }
}
