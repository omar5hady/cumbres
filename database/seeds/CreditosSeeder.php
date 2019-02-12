<?php

use Illuminate\Database\Seeder;
use App\Tipo_credito;

class CreditosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //BANORTE
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'Banorte'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'Banorte'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Banorte'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'Banorte'
        ]);
        
        // SANTANDER
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'Santander'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'Santander'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Santander'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'Santander'
        ]);

        // Banregio
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'Banregio'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'Banregio'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Banregio'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'Banregio'
        ]);
        
        //BanBahio
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'BanBajío'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'BanBajío'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'BanBajío'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'BanBajío'
        ]);
        
        //Banamex
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'Banamex'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'Banamex'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Banamex'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'Banamex'
        ]);
        
        
        //BBVA Bancomer
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'BBVA Bancomer'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'BBVA Bancomer'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'BBVA Bancomer'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'BBVA Bancomer'
        ]);
        
        //Scotiabank
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'Scotiabank'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'Scotiabank'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Scotiabank'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'Scotiabank'
        ]);

        //HSBC
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'HSBC'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'HSBC'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'HSBC'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'HSBC'
        ]);
        
        //PEMEX
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'PEMEX'
        ]);

        //Caja Real del Potosí
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Caja Real del Potosí'
        ]);

        //ISSFAM
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'ISSFAM'
        ]);
        //ISSEG
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'ISSEG'
        ]);
        //IMSS
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'IMSS'
        ]);
        //CFE
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'CFE'
        ]);
        //TELMEX
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Telmex'
        ]);
        //Pensiones
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Pensiones'
        ]);
        //Caja Popular Mexicana
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Caja Popular Mexicana'
        ]);
        //Grupo Cumbres
        Tipo_credito::create([
            'nombre' => 'Crédito Directo',
            'institucion_fin' => 'Grupo Cumbres'
        ]);
        //FIVITE
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'FIVITE'
        ]);
        
        //AFIRME
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'Afirme'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'Afirme'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Afirme'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'Afirme'
        ]);
        
        //Infonavit
        Tipo_credito::create([
            'nombre' => '2do. Crédito',
            'institucion_fin' => 'Infonavit'
        ]);
        Tipo_credito::create([
            'nombre' => 'Infonavit Total',
            'institucion_fin' => 'Infonavit'
        ]);
        Tipo_credito::create([
            'nombre' => 'Infonavit Tradicional',
            'institucion_fin' => 'Infonavit'
        ]);


        //Fovissste
        Tipo_credito::create([
            'nombre' => 'Tradicional Fovissste',
            'institucion_fin' => 'Fovissste'
        ]);
        Tipo_credito::create([
            'nombre' => '2do. Crédito',
            'institucion_fin' => 'Fovissste'
        ]);
        

        //Inbursa
        Tipo_credito::create([
            'nombre' => 'Alia2',
            'institucion_fin' => 'Inbursa'
        ]);
        Tipo_credito::create([
            'nombre' => 'Cofinavit',
            'institucion_fin' => 'Inbursa'
        ]);
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Inbursa'
        ]);
        Tipo_credito::create([
            'nombre' => 'Respalda2',
            'institucion_fin' => 'Inbursa'
        ]);

        //Banco Inmobiliario
        Tipo_credito::create([
            'nombre' => 'Tradicional',
            'institucion_fin' => 'Banco Inmobiliario Mexicano'
        ]);

    }
}
