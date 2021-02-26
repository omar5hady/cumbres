<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('documento');
            $table->string('categoria');
            $table->timestamps();
        });

        DB::table('cat_documentos')->
        insert(array('id'=>'1', 'categoria'=>'Proyecto', 'documento'=>'Plano de ubicación con equipamiento urbano'));
        DB::table('cat_documentos')->
        insert(array('id'=>'2', 'categoria'=>'Proyecto', 'documento'=>'Descripción del proyecto'));
        DB::table('cat_documentos')->
        insert(array('id'=>'3', 'categoria'=>'Proyecto', 'documento'=>'Listado de viviendas'));
        DB::table('cat_documentos')->
        insert(array('id'=>'4', 'categoria'=>'Proyecto', 'documento'=>'Titulo de propiedad de las garantías'));
        DB::table('cat_documentos')->
        insert(array('id'=>'5', 'categoria'=>'Proyecto', 'documento'=>'Boleta predial'));
        DB::table('cat_documentos')->
        insert(array('id'=>'6', 'categoria'=>'Permisos y Licencias', 'documento'=>'Autorización del conjunto urbano'));
        DB::table('cat_documentos')->
        insert(array('id'=>'7', 'categoria'=>'Permisos y Licencias', 'documento'=>'Licencia de construcción'));
        DB::table('cat_documentos')->
        insert(array('id'=>'8', 'categoria'=>'Permisos y Licencias', 'documento'=>'Uso de suelo'));
        DB::table('cat_documentos')->
        insert(array('id'=>'9', 'categoria'=>'Permisos y Licencias', 'documento'=>'Estudios de impacto ambiental'));
        DB::table('cat_documentos')->
        insert(array('id'=>'10', 'categoria'=>'Permisos y Licencias', 'documento'=>'Estudio y factibilidad de agua potable y alcantarillado'));
        DB::table('cat_documentos')->
        insert(array('id'=>'11', 'categoria'=>'Permisos y Licencias', 'documento'=>'Estudio y factibilidad de energía eléctrica'));
        DB::table('cat_documentos')->
        insert(array('id'=>'12', 'categoria'=>'Mercado', 'documento'=>'Estudio de mercado'));
        DB::table('cat_documentos')->
        insert(array('id'=>'13', 'categoria'=>'Mercado', 'documento'=>'Ubicación en plano de la ciudad de la competencia'));
        DB::table('cat_documentos')->
        insert(array('id'=>'14', 'categoria'=>'Mercado', 'documento'=>'Plan y programa de construcción, venta e individualización'));
        DB::table('cat_documentos')->
        insert(array('id'=>'15', 'categoria'=>'Financiero', 'documento'=>'Flujo de efectivos del proyecto'));
        DB::table('cat_documentos')->
        insert(array('id'=>'16', 'categoria'=>'Técnico', 'documento'=>'Registros del perito responsable de la obra'));
        DB::table('cat_documentos')->
        insert(array('id'=>'17', 'categoria'=>'Edificación', 'documento'=>'Memoria descriptiva y de calculo'));
        DB::table('cat_documentos')->
        insert(array('id'=>'18', 'categoria'=>'Edificación', 'documento'=>'Planos de edificación'));
        DB::table('cat_documentos')->
        insert(array('id'=>'19', 'categoria'=>'Edificación', 'documento'=>'Estudio de mecánica de suelos'));
        DB::table('cat_documentos')->
        insert(array('id'=>'20', 'categoria'=>'Edificación', 'documento'=>'Presupuesto por conceptos de edificación'));
        DB::table('cat_documentos')->
        insert(array('id'=>'21', 'categoria'=>'Edificación', 'documento'=>'Especificaciones de construcción'));
        DB::table('cat_documentos')->
        insert(array('id'=>'22', 'categoria'=>'Edificación', 'documento'=>'Programa de obra'));
        DB::table('cat_documentos')->
        insert(array('id'=>'23', 'categoria'=>'Urbanizacion', 'documento'=>'Planos de urbanización'));
        DB::table('cat_documentos')->
        insert(array('id'=>'24', 'categoria'=>'Urbanizacion', 'documento'=>'Especificaciones'));
        DB::table('cat_documentos')->
        insert(array('id'=>'25', 'categoria'=>'Urbanizacion', 'documento'=>'Presupuesto por conceptos de urbanización'));
        DB::table('cat_documentos')->
        insert(array('id'=>'26', 'categoria'=>'Urbanizacion', 'documento'=>'Programa de obra'));
        DB::table('cat_documentos')->
        insert(array('id'=>'27', 'categoria'=>'Proyecto', 'documento'=>'Solicitud de crédito'));
        DB::table('cat_documentos')->
        insert(array('id'=>'28', 'categoria'=>'Proyecto', 'documento'=>'Escritura del terreno'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_documentos');
    }
}
