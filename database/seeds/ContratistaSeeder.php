<?php

use Illuminate\Database\Seeder;
use App\Contratista;

class ContratistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contratista::create([
            'nombre' => 'Constructora Damove, SA de CV',
            'tipo' => 0,
            'rfc' => 'CDA070901I76',
            'direccion' => 'Quetzalcóatl No. 105',
            'colonia' => 'Jardines de Oriente',
            'cp' => '78390',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'representante' => 'Ing. Juan José Vázquez Ramírez',
            'IMSS' => 'E397257310-7',
            'telefono' => '01(444)5676272'
        ]);
        Contratista::create([
            'nombre' => 'Construcciones Entereza, SA de CV',
            'tipo' => 0,
            'rfc' => 'CEN121008MU9',
            'direccion' => 'Alfredo M Terrazas No. 34',
            'colonia' => 'Tequisquiapan',
            'cp' => '78250',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'representante' => 'Ing. Fco. Javier Martínez Costilla',
            'IMSS' => 'I731851510-8',
            'telefono' => '01(444)1682093'
        ]);
        Contratista::create([
            'nombre' => 'CONCRE-TA SH, S.A. DE C.V. ',
            'tipo' => 0,
            'rfc' => 'CSH170113JH5',
            'direccion' => 'Venustiano Carranza No. 475 Int. 8',
            'colonia' => 'Centro',
            'cp' => '78391',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'representante' => 'Ing. Hector Enriquez Perez',
            'IMSS' => 'E411871210-4',
            'telefono' => '01(444)2440689'
        ]);
        Contratista::create([
            'nombre' => 'Constructora INCATSSA S.A. DE C.V.',
            'tipo' => 0,
            'rfc' => 'CIN9812219Q8 ',
            'direccion' => 'Himno Nacional No. 1967 Int. B',
            'colonia' => 'Fracc. Tangamanga',
            'cp' => '0',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'representante' => 'Ing. Toribio Salas Rodriguez',
            'IMSS' => 'E3952974101-1',
            'telefono' => '01(444)8110247'
        ]);
        Contratista::create([
            'nombre' => 'José Gerardo Mendez Montaño',
            'tipo' => 1,
            'rfc' => 'MEMG581003855',
            'direccion' => 'Calle Tauro  No. 432',
            'colonia' => 'Fracc. Central',
            'cp' => '78399',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'representante' => 'Ing. José Gerardo Mendez Montaño',
            'IMSS' => 'E396038210-7',
            'telefono' => '01(444)1510259'
        ]);
        Contratista::create([
            'nombre' => 'Marco Antonio Avalos Castillo',
            'tipo' => 1,
            'rfc' => 'AACM8405318F5',
            'direccion' => 'Tabachines No 133',
            'colonia' => 'Fracc. Los Fresnos',
            'cp' => '78433',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'representante' => 'Arq. Marco Antonio Avalos Castillo',
            'IMSS' => 'E411861510-9',
            'telefono' => '01(444)8335738'
        ]);
        Contratista::create([
            'nombre' => 'ICODISE S.A. DE C.V.',
            'tipo' => 0,
            'rfc' => 'ICO1302209J0',
            'direccion' => 'Alfredo M Terrazas No. 34',
            'colonia' => 'Tequisquiapan',
            'cp' => '78250',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'representante' => 'Ing. José Luis Martínez Costilla',
            'IMSS' => 'Y731894910-9',
            'telefono' => '01(444)1682093'
        ]);
    }
}
