<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notarias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notaria');
            $table->string('titular');
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('direccion')->nullable();
            $table->string('colonia')->nullable();
            $table->integer('cp')->nullable();
            $table->string('telefono_1', 10)->nullable();
            $table->string('telefono_2', 10)->nullable();
            $table->string('telefono_3', 10)->nullable();
            $table->string('telefono_4', 10)->nullable();
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notarias');
    }
}
