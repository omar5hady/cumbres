<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevConcretaniaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dev_concretania', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contrato_id');
            $table->date('fecha_dev');
            $table->double('monto');
            $table->string('cheque');
            $table->string('cuenta');
            $table->string('observaciones')->nullable();
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
        Schema::dropIfExists('dev_concretania');
    }
}
