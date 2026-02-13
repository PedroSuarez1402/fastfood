<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Crear Categorías
        $comidas = Categoria::create(['nombre' => 'Comidas', 'descripcion' => 'Platos principales']);
        $bebidas = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Refrescos y jugos']);

        // 2. Crear Productos Comidas
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

        // 3. Crear Productos Bebidas
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
    }
}