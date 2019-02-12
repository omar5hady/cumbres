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
            $table->string('telefono1',12)->nullable();
            $table->string('telefono2',12)->nullable();
            $table->string('telefono3',12)->nullable();
            $table->string('telefono4',12)->nullable();
            $table->string('pagina_web',70)->nullable();
            $table->timestamps();
        });
        DB::table('instituciones_financiamiento')->
            insert(array('id'=>'1','nombre'=>'BANORTE', 
                                'telefono1'=>'8134429',
                                'telefono2'=>'8136992',
                        'pagina_web'=>'www.banorte.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'2','nombre'=>'SANTANDER', 
                            'telefono1'=>'8256571',
                            'telefono2'=>'8256691',
                            'pagina_web'=>'www.santander.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'3','nombre'=>'METROFINANCIERA', 
                            'telefono1'=>'8122787',
                            'pagina_web'=>'www.metrofinanciera.com.mx'));

        
        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'4','nombre'=>'BANREGIO', 
                            'telefono1'=>'8142466',
                            'telefono2'=>'8142460',
                            'pagina_web'=>'www.banregio.com.mx'));
        
        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'5','nombre'=>'HIP NACIONAL', 
                            'telefono1'=>'4999600',
                            'pagina_web'=>'www.hipnal.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'6','nombre'=>'BANCO DEL BAJIO', 
                            'telefono1'=>'8331902',
                            'telefono2'=>'8112170',
                            'pagina_web'=>'www.bb.com.mx'));
    
        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'7','nombre'=>'BANAMEX', 
                            'telefono1'=>'8136387',
                            'pagina_web'=>'www.banamex.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'8','nombre'=>'BANCOMER', 
                            'telefono1'=>'8263791',
                            'telefono2'=>'8263600',
                            'pagina_web'=>'www.bancomer.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'9','nombre'=>'ING', 
                            'telefono1'=>'8172496',
                            'telefono2'=>'8330846',
                            'telefono3'=>'8119427',
                            'pagina_web'=>'www.ing.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'10','nombre'=>'ABC Capital', 
                            'telefono1'=>'8179482',
                            'telefono2'=>'8179604',
                            'telefono3'=>'8338810',
                            'pagina_web'=>'www.creditoycasa.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'11','nombre'=>'CASA MEXICANA', 
                            'telefono1'=>'8415512',
                            'telefono2'=>'8415545',
                            'telefono3'=>'8416019',
                            'pagina_web'=>'www.hcasamex.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'12','nombre'=>'SCOTIABANK', 
                            'telefono1'=>'8136273',
                            'telefono2'=>'8136372',
                            'telefono3'=>'8136384',
                            'telefono4'=>'8136587',
                            'pagina_web'=>'www.scotiabank.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'14','nombre'=>'INFONAVIT', 
                            'telefono1'=>'8180133',
                            'pagina_web'=>'www.infonavit.org.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'15','nombre'=>'SU CASITA', 
                            'telefono1'=>'8389000',
                            'pagina_web'=>'www.sucasita.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'16','nombre'=>'HSBC', 
                            'pagina_web'=>'www.hsbc.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'17','nombre'=>'FIVITE', 
                            'telefono1'=>'1519070',
                            'telefono2'=>'1510968',
                            'pagina_web'=>'www_snte26.org.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'18','nombre'=>'OPERACION DIRECTA', 
                            'telefono1'=>'8334683',
                            'telefono2'=>'8334684',
                            'telefono3'=>'8334685',
                            'pagina_web'=>'www.grupocumbres.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'19','nombre'=>'PEMEX', 
                            'telefono1'=>'5519442500',
                            'pagina_web'=>'www.pemex.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'20','nombre'=>'TELMEX', 
                            'telefono1'=>'8121666',
                            'pagina_web'=>'www.telmex.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'21','nombre'=>'IMSS', 
                            'telefono1'=>'018006232323',
                            'pagina_web'=>'www.imss.gob.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'22','nombre'=>'PENSIONES', 
                            'telefono1'=>'1441800',
                            'telefono2'=>'8126509',
                            'pagina_web'=>'www.pensionesslp.gob.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'23','nombre'=>'FOVISSSTE', 
                            'telefono1'=>'018003684783',
                            'telefono2'=>'8115494',
                            'telefono3'=>'8115512',
                            'telefono4'=>'8115985',
                            'pagina_web'=>'www.fovissste.gob.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'24','nombre'=>'GCC', 
                            'telefono1'=>'4448334683',
                            'pagina_web'=>'www.grupocumbres.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'25','nombre'=>'CORPORACION HIPOTECARIA', 
                            'telefono1'=>'1510041',
                            'telefono2'=>'1510040',
                            'pagina_web'=>'corporacionhipotecaria_slp@yahoo.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'26','nombre'=>'AUTOFIN MEXICO', 
                            'telefono1'=>'54820300',
                            'pagina_web'=>'www.autofin.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'27','nombre'=>'SENSA', 
                            'telefono1'=>'1510987',
                            'telefono2'=>'4444293797',
                            'pagina_web'=>'www.sensasofom.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'28','nombre'=>'C.F.E.', 
                            'telefono1'=>'018002233071',
                            'pagina_web'=>'www.cfe.gob.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'29','nombre'=>'BANJERCITO', 
                            'telefono1'=>'015554262546',
                            'telefono2'=>'015554262547',
                            'pagina_web'=>'www.gob.mx/banjercito'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'30','nombre'=>'FOVISSSTE (CASA MEXICANA)', 
                            'pagina_web'=>'www.fovissste.gob.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'31','nombre'=>'AUTOFIN MONTERREY N.L.', 
                            'telefono1'=>'8156600',
                            'pagina_web'=>'www.autofinauto.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'32','nombre'=>'CITY HOME', 
                            'telefono1'=>'1510040',
                            'telefono2'=>'1510041'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'33','nombre'=>'CAJA REAL DEL POTOSI', 
                            'telefono1'=>'1371000',
                            'pagina_web'=>'www.cajarealdelpotosi.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'34','nombre'=>'INFONAVIT-FOVISSSTE'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'35','nombre'=>'INFONAVIT-FOVISSSTE (BAJIO)'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'36','nombre'=>'BANSI', 
                            'telefono1'=>'018002267400',
                            'pagina_web'=>'www.bansi.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'37','nombre'=>'HIPOTECARIA CREA MAS', 
                            'pagina_web'=>'www.hcreamas.com.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'38','nombre'=>'AFIRME', 
                            'telefono1'=>'8324200',
                            'pagina_web'=>'www.afirme.com'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'39','nombre'=>'GAMU', 
                            'pagina_web'=>'www.gamu.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'40','nombre'=>'DAE'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'41','nombre'=>'ISSEG', 
                            'telefono1'=>'4737351400',
                            'pagina_web'=>'www.isseg.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'42','nombre'=>'FOVISSSTE(BAJIO)'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'43','nombre'=>'CAJA POPULAR MEXICANA', 
                            'telefono1'=>'018007100800',
                            'pagina_web'=>'www.cpm.coop'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'44','nombre'=>'UASLP', 
                            'pagina_web'=>'www.uaslp.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'45','nombre'=>'FINA SOFOM', 
                            'telefono1'=>'8101135',
                            'telefono2'=>'8907557',
                            'pagina_web'=>'www.finasofom.mx'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'46','nombre'=>'ISSFAM', 
                            'telefono1'=>'015521220601',
                            'pagina_web'=>'www.gob.mx/issfam'));

        DB::table('instituciones_financiamiento')->
        insert(array('id'=>'47','nombre'=>'HIPOTECARIA BRADA', 
                            'pagina_web'=>'www.bradahipotecaria.com.mx'));
                                     
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
