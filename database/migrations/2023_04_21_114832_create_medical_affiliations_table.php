<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalAffiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_affiliations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('record_id');
            $table->string('proveedor',50);
            $table->string('poliza',15);
            $table->string('tipo',30)->nullable();
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
        Schema::dropIfExists('medical_affiliations');
    }
}
