<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario Administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@fastfood.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('SuperAdmin');

        // Crear usuario Cocinero
        $cocinero = User::create([
            'name' => 'Cocinero',
            'email' => 'cocinero@fastfood.com',
            'password' => Hash::make('password'),
        ]);
        $cocinero->assignRole('Cocina');

        // Crear usuario Mesero
        $mesero = User::create([
            'name' => 'Mesero',
            'email' => 'mesero@fastfood.com',
            'password' => Hash::make('password'),
        ]);
        $mesero->assignRole('Mesero');
    }
}