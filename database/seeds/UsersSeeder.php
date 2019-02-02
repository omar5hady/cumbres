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
    }
}
