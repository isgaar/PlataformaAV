<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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

        // Crear usuario Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);
        $admin->assignRole('admin');

        // Crear usuario Teacher
        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password123')
        ]);
        $teacher->assignRole('teacher');

        // Crear usuario Student
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('password123')
        ]);
        $student->assignRole('student');

        $this->command->info('Usuarios con roles creados correctamente.');
    }
}
