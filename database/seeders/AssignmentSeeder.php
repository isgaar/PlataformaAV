<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;

class AssignmentSeeder extends Seeder
{
    public function run()
    {
        // Crear algunas asignaciones de ejemplo
        Assignment::create([
            'user_id' => 2, // Profesor Juan
            'group_id' => 1,
            'subject' => 'Matemáticas'
        ]);

        Assignment::create([
            'user_id' => 2, // Profesor Juan
            'group_id' => 2,
            'subject' => 'Ciencias'
        ]);
    }
}
