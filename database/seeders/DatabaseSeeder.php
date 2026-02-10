<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ejecutar seeder de roles y permisos primero
        $this->call(RolesAndPermissionsSeeder::class);

        $faker = Faker::create();

        // Crear usuario Administrador con rol SuperAdmin
        $admin = User::factory()->create([
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

        // Categorías
        $comidas = Categoria::create(['nombre' => 'Comidas', 'descripcion' => 'Platos principales']);
        $bebidas = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Refrescos y jugos']);

        // Productos Comidas
        $comidas->productos()->createMany([
            [
                'nombre' => 'Hamburguesa Clásica',
                'descripcion' => 'Carne de res, lechuga, tomate y queso.',
                'precio' => 18000,
                'imagen' => $faker->imageUrl(640, 480, 'food', true),
            ],
            [
                'nombre' => 'Perro Caliente',
                'descripcion' => 'Pan artesanal con salchicha y papas.',
                'precio' => 12000,
                'imagen' => $faker->imageUrl(640, 480, 'food', true),
            ],
        ]);

        // Productos Bebidas
        $bebidas->productos()->createMany([
            [
                'nombre' => 'Gaseosa',
                'descripcion' => 'Botella 400 ml',
                'precio' => 4000,
                'imagen' => $faker->imageUrl(640, 480, 'drink', true),
            ],
            [
                'nombre' => 'Jugo Natural',
                'descripcion' => 'Fresa, mango o guanábana.',
                'precio' => 6000,
                'imagen' => $faker->imageUrl(640, 480, 'drink', true),
            ],
        ]);

        // Mesas
        Mesa::create(['numero' => 1, 'capacidad' => 4, 'estado' => 'disponible']);
        Mesa::create(['numero' => 2, 'capacidad' => 4, 'estado' => 'disponible']);
        Mesa::create(['numero' => 3, 'capacidad' => 4, 'estado' => 'disponible']);
        Mesa::create(['numero' => 4, 'capacidad' => 8, 'estado' => 'disponible']);
    }
}
