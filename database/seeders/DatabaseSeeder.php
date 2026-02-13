<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Primero roles y permisos (Indispensable)
            RolesAndPermissionsSeeder::class,
            
            // 2. Luego usuarios (Dependen de los roles)
            UserSeeder::class,
            
            // 3. Datos independientes del negocio
            ProductSeeder::class,
            MesaSeeder::class,
        ]);
    }
}