<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    public function run()
    {
        // Crear algunos grados de ejemplo
        Grade::create(['name' => 'Primero']);
        Grade::create(['name' => 'Segundo']);
        Grade::create(['name' => 'Tercero']);
    }
}

