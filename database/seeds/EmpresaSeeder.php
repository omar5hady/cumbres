<?php

use Illuminate\Database\Seeder;
use App\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Empresa::create([
            'id' => 1,
            'nombre' => 'Grupo Constructor Cumbres',
            'direccion' => 'Nicolas Zapata #790',
            'cp' => '78250',
            'colonia' => 'Tequisquiapan',
            'estado' => 'San Luis Potosí',
            'ciudad' => 'San Luis Potosí',
            'telefono' => '8330307',
            'ext' => ''
        ]);
    }
}
