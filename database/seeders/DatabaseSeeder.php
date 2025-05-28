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
            RoleSeeder::class,      // Roles deben ir primero
            SchoolSeeder::class,    // Escuelas
            GradeSeeder::class,     // Grados
            TurnoSeeder::class,     // Turnos
            GroupSeeder::class,     // Grupos
            PeriodSeeder::class,    //Periodos
            UserSeeder::class,      // Usuarios (después de grupos y escuelas)
            AssignmentSeeder::class // Asignaciones (requiere users y grupos)
        ]);
    }
}
