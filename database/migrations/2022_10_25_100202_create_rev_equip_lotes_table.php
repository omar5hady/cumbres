<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevEquipLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rev_equip_lotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solicitud_id');
            $table->string('categoria',100);
            $table->string('subcategoria',100);
            $table->string('concepto',100);
            $table->boolean('check1')->default(0)->nullable();
            $table->boolean('check2')->default(0)->nullable();
            $table->boolean('check3')->default(0)->nullable();
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
        Schema::dropIfExists('rev_equip_lotes');
    }
}
