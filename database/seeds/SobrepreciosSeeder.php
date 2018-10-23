<?php

use Illuminate\Database\Seeder;
use App\Sobreprecio;

class SobrepreciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sobreprecio::create([
            'nombre' => 'Avenida'
        ]);
        Sobreprecio::create([
            'nombre' => 'Avenida y Esquina'
        ]);
        Sobreprecio::create([
            'nombre' => 'Esquina'
        ]);
        Sobreprecio::create([
            'nombre' => 'Obra colindante a área verde'
        ]);
        Sobreprecio::create([
            'nombre' => 'Obra de fachada lateral'
        ]);
        Sobreprecio::create([
            'nombre' => 'Protecciones en la vivienda'
        ]);
    }
}
