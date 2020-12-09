<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportesExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importes_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->double('impExtra')->default(0);
            $table->date('fechaExtra')->nullable();
            $table->integer('aviso_id');
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
        Schema::dropIfExists('importes_extras');
    }
}
