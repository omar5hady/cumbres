<?php

use Illuminate\Database\Seeder;
use App\Personal;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Personal::create([
            'id' => 2,
            'departamento_id' => 7,
            'Apellidos' => 'Guzman Vargas',
            'Nombre' => 'Jose Juan',
            'f_nacimiento' => '1995-07-02',
            'rfc' => 'GUVJ950702AC3',
            'direccion' => 'Jacarandas',
            'telefono' => '88888888',
            'celular' => '4444444444',
            'email' => 'Juan@email.com',
            'activo' => 1
        ]);

        User::create([
            'id' => 2,
            'usuario' => 'admin',
            'password' => bcrypt('admin123'),
            'condicion' => '1',
            'rol_id' => '1',

           'administracion' => 1,
           'desarrollo' => 1,
           'precios' => 1,
           'obra' => 1,
           'ventas' => 1,
           'acceso' => 1,
           'reportes' => 1,

            //Administracion
           'departamentos'=> 1,
           'personas'=> 1,
           'empresas'=> 1,
           'medios_public'=> 1,
           'lugares_contacto'=> 1,
           'servicios'=> 1,
           'inst_financiamiento'=> 1,
           'tipos_credito'=> 1,
           'asig_servicios'=> 1,
           'mis_asesores'=> 1,


            //Desarrollo
           'fraccionamiento'=> 1,
           'etapas'=> 1,
           'modelos'=> 1,
           'lotes'=> 1,
           'asign_modelos'=> 1,
           'licencias'=> 1,
           'acta_terminacion'=> 1,
           'p_etapa'=> 1,
           'p_fraccionamiento'=> 1,

            //Precios
           'precios_etapas'=> 1,
           'sobreprecios'=> 1,
           'paquetes'=> 1,
           'promociones'=> 1,

            //Obra
           'contratistas'=> 1,
           'ini_obra'=> 1,
           'aviso_obra'=> 1,
           'partidas'=> 1,
           'avance'=> 1,

            //Ventas
            'lotes_disp'=> 1,
           'mis_prospectos'=> 1,
           'simulacion_credito'=> 1,
           'hist_simulaciones'=> 1,
           'hist_creditos'=> 1,
           'contratos'=> 1,

            //Acceso
           'usuarios'=> 1,
           'roles'=> 1,

            //Reportes
           'mejora'=> 1
        ]);

        Personal::create([
            'id' => 3,
            'departamento_id' => 7,
            'Apellidos' => 'Ramos Vázquez',
            'Nombre' => 'Jaime Omar',
            'f_nacimiento' => '1991-12-11',
            'rfc' => 'RAVJ911211C41',
            'direccion' => '3a Priv. de Prol. Jaime Sordo #100',
            'telefono' => '4448122539',
            'celular' => '4444605232',
            'email' => 'omar.ramos@grupocumbres.com',
            'activo' => 1
        ]);

        User::create([
            'id' => 3,
            'usuario' => 'shady',
            'password' => bcrypt('admin123'),
            'condicion' => '1',
            'rol_id' => '1',

            'administracion' => 1,
            'desarrollo' => 1,
            'precios' => 1,
            'obra' => 1,
            'ventas' => 1,
            'acceso' => 1,
            'reportes' => 1,

                //Administracion
            'departamentos'=> 1,
            'personas'=> 1,
            'empresas'=> 1,
            'medios_public'=> 1,
            'lugares_contacto'=> 1,
            'servicios'=> 1,
            'inst_financiamiento'=> 1,
            'tipos_credito'=> 1,
            'asig_servicios'=> 1,
            'mis_asesores'=> 1,


                //Desarrollo
            'fraccionamiento'=> 1,
            'etapas'=> 1,
            'modelos'=> 1,
            'lotes'=> 1,
            'asign_modelos'=> 1,
            'licencias'=> 1,
            'acta_terminacion'=> 1,
            'p_etapa'=> 1,
            'p_fraccionamiento'=> 1,

                //Precios
            'precios_etapas'=> 1,
            'sobreprecios'=> 1,
            'paquetes'=> 1,
            'promociones'=> 1,

                //Obra
            'contratistas'=> 1,
            'ini_obra'=> 1,
            'aviso_obra'=> 1,
            'partidas'=> 1,
            'avance'=> 1,

                //Ventas
                'lotes_disp'=> 1,
            'mis_prospectos'=> 1,
            'simulacion_credito'=> 1,
            'hist_simulaciones'=> 1,
            'hist_creditos'=> 1,
            'contratos'=> 1,

                //Acceso
            'usuarios'=> 1,
            'roles'=> 1,

                //Reportes
            'mejora'=> 1
        ]);
    }
}
