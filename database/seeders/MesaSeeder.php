<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    public function run(): void
    {
        Mesa::create(['numero' => 1, 'capacidad' => 4, 'estado' => 'disponible']);
        Mesa::create(['numero' => 2, 'capacidad' => 4, 'estado' => 'disponible']);
        Mesa::create(['numero' => 3, 'capacidad' => 4, 'estado' => 'disponible']);
        Mesa::create(['numero' => 4, 'capacidad' => 8, 'estado' => 'disponible']);
    }
}