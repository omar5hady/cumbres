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
            'rol_id' => '1'
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
            'rol_id' => '1'
        ]);
    }
}
