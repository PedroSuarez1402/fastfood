<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\User;
use App\Models\Producto;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Categorías
        $comidas = Categoria::create(['nombre' => 'Comidas', 'descripcion' => 'Platos principales']);
        $bebidas = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Refrescos y jugos']);

        // Productos
        $comidas->productos()->createMany([
            ['nombre' => 'Hamburguesa Clásica', 'descripcion' => 'Carne de res, lechuga, tomate y queso.', 'precio' => 18000],
            ['nombre' => 'Perro Caliente', 'descripcion' => 'Pan artesanal con salchicha y papas.', 'precio' => 12000],
        ]);

        $bebidas->productos()->createMany([
            ['nombre' => 'Gaseosa', 'descripcion' => 'Botella 400 ml', 'precio' => 4000],
            ['nombre' => 'Jugo Natural', 'descripcion' => 'Fresa, mango o guanábana.', 'precio' => 6000],
        ]);

        // Mesas
        Mesa::create(['numero' => 1, 'capacidad' => 4, 'estado' => 'disponible']);
    }
}
