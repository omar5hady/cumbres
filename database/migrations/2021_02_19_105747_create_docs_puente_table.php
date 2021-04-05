<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsPuenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_puente', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('puente_id');
            $table->string('descripcion');
            $table->string('archivo')->nullable();
            $table->integer('clasificacion');
            $table->date('fecha_entrega')->nullable();
            $table->text('notas')->nullable();
            $table->string('user_alta')->nullable();
            $table->string('user_confirm')->nullable();
            $table->date('fecha_confirm')->nullable();
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
        Schema::dropIfExists('docs_puente');
    }
}
