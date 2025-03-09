<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        // Crear algunas escuelas de ejemplo
        School::create([
            'name' => 'Escuela Primaria El Sol',
            'address' => 'Calle Sol 123, Ciudad'
        ]);

        School::create([
            'name' => 'Escuela Secundaria Los Pinos',
            'address' => 'Calle Pinos 456, Ciudad'
        ]);
    }
}
