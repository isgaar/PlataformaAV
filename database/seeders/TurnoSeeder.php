<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turno;

class TurnoSeeder extends Seeder
{
    public function run()
    {
        // Crear algunos turnos de ejemplo
        Turno::create(['nombre' => 'Matutino']);
        Turno::create(['nombre' => 'Vespertino']);
    }
}

