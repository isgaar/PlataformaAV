<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run()
    {
        // Crear algunos grupos de ejemplo
        Group::create(['name' => 'Grupo A', 'grade_id' => 1]);
        Group::create(['name' => 'Grupo B', 'grade_id' => 2]);
    }
}
