<?php

use Illuminate\Database\Seeder;
use App\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departamento::create([
            'id_departamento' => 1,
            'departamento' => 'Dirección',
            'user_alta' => 1
        ]);
        Departamento::create([
            'departamento' => 'Obra y construcción',
            'user_alta' => 1
        ]);
        Departamento::create([
            'departamento' => 'Proyectos',
            'user_alta' => 1
        ]);
        Departamento::create([
            'departamento' => 'Postventa',
            'user_alta' => 1
        ]);
        Departamento::create([
            'departamento' => 'Presupuestos',
            'user_alta' => 1
        ]);
        Departamento::create([
            'departamento' => 'Contabilidad',
            'user_alta' => 1
        ]);
        Departamento::create([
            'departamento' => 'Sistemas',
            'user_alta' => 1
        ]);
        Departamento::create([
            'departamento' => 'Clientes',
            'user_alta' => 1
        ]);
        
    }
}
