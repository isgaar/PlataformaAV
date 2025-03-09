<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Turno;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Verificar que los roles existen
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        if (!$adminRole || !$teacherRole || !$studentRole) {
            $this->command->error("Asegúrate de ejecutar el RoleSeeder antes de UserSeeder.");
            return;
        }

        // Obtener datos relacionados
        $school = School::first();
        $grade = Grade::first();
        $group = Group::first();
        $turno = Turno::first();

        // Crear usuario Admin
        $admin = User::create([
            'name' => 'Admin User',
            'last_name' => 'Admin',
            'second_last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin', // 👈 Asegurar que el campo role se incluya
            'school_id' => $school->id ?? null,
            'grade_id' => $grade->id ?? null,
            'group_id' => $group->id ?? null,
            'turno_id' => $turno->id ?? null
        ]);
        $admin->assignRole('admin');

        // Crear usuario Teacher
        $teacher = User::create([
            'name' => 'Teacher User',
            'last_name' => 'Teacher',
            'second_last_name' => 'User',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher', // 👈 Asegurar que el campo role se incluya
            'school_id' => $school->id ?? null,
            'grade_id' => $grade->id ?? null,
            'group_id' => $group->id ?? null,
            'turno_id' => $turno->id ?? null
        ]);
        $teacher->assignRole('teacher');

        // Crear usuario Student
        $student = User::create([
            'name' => 'Student User',
            'last_name' => 'Student',
            'second_last_name' => 'User',
            'email' => 'student@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student', // 👈 Asegurar que el campo role se incluya
            'school_id' => $school->id ?? null,
            'grade_id' => $grade->id ?? null,
            'group_id' => $group->id ?? null,
            'turno_id' => $turno->id ?? null
        ]);
        $student->assignRole('student');

        $this->command->info('Usuarios con roles creados correctamente.');
    }
}
