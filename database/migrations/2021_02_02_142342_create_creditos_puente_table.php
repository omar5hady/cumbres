<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditosPuenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos_puente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('banco');
            $table->float('interes')->default(0);
            $table->date('fecha_solic')->nullable();
            $table->boolean('status')->default(0);
            $table->double('total')->default(0);
            $table->double('cobrado')->default(0);
            $table->string('folio');
            $table->double('terreno')->default(0);
            $table->float('apertura')->default(0);
            $table->integer('fraccionamiento');
            $table->integer('etapa_id');
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
        Schema::dropIfExists('creditos_puente');
    }
}
