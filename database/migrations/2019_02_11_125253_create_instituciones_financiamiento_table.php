<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitucionesFinanciamientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituciones_financiamiento', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50);
            $table->timestamps();
        });
        DB::table('instituciones_financiamiento')->
            insert(array('id'=>'1','nombre'=>'Banorte'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'2','nombre'=>'Santander'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'3','nombre'=>'Banregio'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'4','nombre'=>'BanBajío'));
    
        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'5','nombre'=>'Banamex'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'6','nombre'=>'BBVA Bancomer'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'7','nombre'=>'Scotiabank'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'8','nombre'=>'HSBC'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'9','nombre'=>'PEMEX'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'10','nombre'=>'Caja Real del Potosí'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'11','nombre'=>'ISSFAM'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'12','nombre'=>'ISSEG'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'13','nombre'=>'IMSS'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'14','nombre'=>'CFE'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'15','nombre'=>'Telmex'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'16','nombre'=>'Pensiones'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'17','nombre'=>'Caja Popular Mexicana'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'18','nombre'=>'Grupo Cumbres'));
        
        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'19','nombre'=>'FIVITE'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'20','nombre'=>'Afirme'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'21','nombre'=>'Infonavit'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'22','nombre'=>'Inbursa'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'23','nombre'=>'Banco Inmobiliario Mexicano'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'24','nombre'=>'Gamu'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'25','nombre'=>'Crea Más'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'26','nombre'=>'ABC Capital'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'27','nombre'=>'Fovissste'));
                                     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituciones_financiamiento');
    }
}
