<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDigitalLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',35);
            $table->string('apellidos',70)->nullable();
            $table->string('celular',10)->nullable();
            $table->string('nss',11)->nullable();
            $table->string('curp',18)->nullable();
            $table->string('rfc',10)->nullable();
            $table->date('f_nacimiento')->nullable();
            $table->string('email',40)->nullable();
            $table->char('sexo',1)->nullable();
            $table->string('asignación')->nullable();
            $table->string('proyecto')->nullable();////////////////
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
        Schema::dropIfExists('digital_leads');
    }
}
