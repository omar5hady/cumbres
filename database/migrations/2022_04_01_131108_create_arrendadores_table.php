<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrendadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrendadores', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('tipo')->default(0);
            $table->string('nombre');
            $table->string('rfc',13)->nullable();
            $table->string('direccion')->nullable();
            $table->string('cp',5)->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('estado')->nullable();
            $table->string('cuenta',10)->nullable();
            $table->string('clabe',20)->nullable();
            $table->string('banco',30)->nullable();
            $table->timestamps();
        });

        DB::table('arrendadores')->insert(
            array(
                'id'=> 1,
                'tipo'=> 1, 
                'nombre'=>'GRUPO CONSTRUCTOR CUMBRES, S. A. DE C.V.',
                'rfc'=> 'GCC000106QS6',
                'direccion'=> 'Manuel Gutiérrez Nájera número 190',
                'cp' => 78230,
                'colonia' => 'Tequisquiapan',
                'municipio' => 'San Luis Potosí',
                'estado' => 'S.L.P.'
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
        Schema::dropIfExists('arrendadores');
    }
}
