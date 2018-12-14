<?php

use Illuminate\Database\Seeder;
use App\Personal;

class PersonalSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Personal::create([
            'id' => 1,
            'departamento_id' => 3,
            'Apellidos' => ' ',
            'Nombre' => 'Sin Asignar',
            'f_nacimiento' => '1999-01-01',
            'rfc' => 'AAAAAAAAAAAAA',
            'direccion' => 'N/A',
            'telefono' => '4',
            'celular' => '444',
            'email' => 'N/A',
            'activo' => 0
        ]);
    }
}
