<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestigosRentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testigos_rentas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100);
            $table->timestamps();
        });

        DB::table('testigos_rentas')->insert(
            array(
                'id'=> 1,
                'nombre'=>'JUAN URIEL ALFARO GALVAN',
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testigos_rentas');
    }
}
