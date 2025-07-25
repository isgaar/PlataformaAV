<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar primero los seeders de datos base
        $this->call([
            RoleSeeder::class,      // Roles
            GradeSeeder::class,     // Grados
            TurnoSeeder::class,     // Turnos
            GroupSeeder::class,     // Grupos
            UserSeeder::class,      // Usuarios (después de grupos y escuelas)
            ActivityUserSeeder::class// Actividades Unity
        ]);
    }
}
