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
        // Verificar que los roles existen en la base de datos
        $roles = [
            'admin' => Role::where('name', 'admin')->first(),
            'teacher' => Role::where('name', 'teacher')->first(),
            'student' => Role::where('name', 'student')->first(),
        ];

        if (in_array(null, $roles)) {
            $this->command->error("❌ Error: Asegúrate de ejecutar el RoleSeeder antes de UserSeeder.");
            return;
        }

        // Obtener datos relacionados (pueden ser `null` si no existen en la BD aún)
        $school = School::first();
        $grade = Grade::first();
        $group = Group::first();
        $turno = Turno::first();

        // Lista de usuarios a crear
        $users = [
            [
                'name' => 'Admin User',
                'last_name' => 'Admin',
                'second_last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'school_id' => null,
                'grade_id' => null,
                'group_id' => null,
                'turno_id' => null
            ],
            [
                'name' => 'Teacher User',
                'last_name' => 'Teacher',
                'second_last_name' => 'User',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'school_id' => $school->id ?? null,
                'grade_id' => $grade->id ?? null,
                'group_id' => $group->id ?? null,
                'turno_id' => $turno->id ?? null
            ],
            [
                'name' => 'Student User',
                'last_name' => 'Student',
                'second_last_name' => 'User',
                'email' => 'student@example.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'school_id' => $school->id ?? null,
                'grade_id' => $grade->id ?? null,
                'group_id' => $group->id ?? null,
                'turno_id' => $turno->id ?? null
            ],
        ];

        // Crear los usuarios y asignar roles con Spatie
        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'last_name' => $userData['last_name'],
                'second_last_name' => $userData['second_last_name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'school_id' => $userData['school_id'],
                'grade_id' => $userData['grade_id'],
                'group_id' => $userData['group_id'],
                'turno_id' => $userData['turno_id'],
            ]);

            // Asignar el rol con Spatie
            $user->assignRole($roles[$userData['role']]->name);
        }

        $this->command->info(' ¡Usuarios con roles creados correctamente!.');
    }
}
