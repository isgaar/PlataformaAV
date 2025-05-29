<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run()
    {
        // Crear grupos A hasta F para grade_id = 1
        foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $letter) {
            Group::create([
                'name' => 'Grupo ' . $letter,
                'grade_id' => 1
            ]);
        }
    }
}
