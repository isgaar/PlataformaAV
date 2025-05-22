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
            'name' => 'Telesecundaria Luis Córdoba Reyes',
            'address' => 'Calle Sol 123, Ciudad'
        ]);

        School::create([
            'name' => 'Telesecundaria Felipe Carrillo Puerto',
            'address' => 'Calle Patria 456, Ciudad'
        ]);

        School::create([
            'name' => 'Telesecundaria Leona Vicario',
            'address' => 'Calle B. Juarez 189, Ciudad'
        ]);
    }
}
