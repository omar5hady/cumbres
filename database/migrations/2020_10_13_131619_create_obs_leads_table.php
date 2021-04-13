<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obs_leads', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('lead_id');
            $table->text('comentario');
            $table->string('usuario',50);
            $table->date('fecha_aviso')->nullable();

            $table->date('visto')->nullable();

            $table->foreign('lead_id')->references('id')->on('digital_leads')->onDelete('cascade');

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
        Schema::dropIfExists('obs_leads');
    }
}
