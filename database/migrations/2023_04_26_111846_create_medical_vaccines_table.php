<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_vaccines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('record_id');
            $table->string('vacuna',80);
            $table->string('lote',50)->nullable();
            $table->foreign('record_id')->references('id')->on('medical_records');
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
        Schema::dropIfExists('medical_vaccines');
    }
}
