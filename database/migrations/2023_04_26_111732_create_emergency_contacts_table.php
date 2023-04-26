<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmergencyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('record_id');
            $table->string('nombre',50);
            $table->string('telefono1',10);
            $table->string('telefono2',10)->nullable();
            $table->string('email',50)->nullable();
            $table->string('direccion',80);
            $table->string('parentesco', 100)->nullable();
            $table->foreign('record_id')->references('id')->on('medical_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergency_contacts');
    }
}
