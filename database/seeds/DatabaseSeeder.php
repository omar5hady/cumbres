<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(DepartamentoSeeder::class);
         $this->call(SobrepreciosSeeder::class);
         $this->call(EmpresaSeeder::class);
         $this->call(PersonalSeed::class);
         $this->call(ContratistaSeeder::class);
         $this->call(UsersSeeder::class);
         $this->call(CreditosSeeder::class);
    }
}
