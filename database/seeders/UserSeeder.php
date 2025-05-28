<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Turno;
use App\Models\Period;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'admin' => Role::where('name', 'admin')->first(),
            'teacher' => Role::where('name', 'teacher')->first(),
            'student' => Role::where('name', 'student')->first(),
        ];

        if (in_array(null, $roles)) {
            $this->command->error("❌ Error: Asegúrate de ejecutar el RoleSeeder antes de UserSeeder.");
            return;
        }

        $school = School::first();
        $grade = Grade::first();
        $group = Group::first();
        $turno = Turno::first();

        // Obtener los periodos existentes
        $periods = Period::all();

        // Lista de usuarios a crear
        // ...
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
                'turno_id' => null,
                'period_id' => null,
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
                'turno_id' => $turno->id ?? null,
                'period_id' => null,
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
                'turno_id' => $turno->id ?? null,
                'period_id' => $periods->first()?->id, // <-- usa el primer periodo disponible
            ],
        ];


        // Crear estudiantes con distintos periodos
        foreach ($periods as $index => $period) {
            $users[] = [
                'name' => "Student $index",
                'last_name' => 'Alumno',
                'second_last_name' => 'Demo',
                'email' => "student$index@example.com",
                'password' => Hash::make('password123'),
                'role' => 'student',
                'school_id' => $school->id ?? null,
                'grade_id' => $grade->id ?? null,
                'group_id' => $group->id ?? null,
                'turno_id' => $turno->id ?? null,
                'period_id' => $period->id, // Asigna cada uno a un periodo distinto
            ];
        }

        // Crear los usuarios y asignar roles
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
                'period_id' => $userData['period_id'],
            ]);

            $user->assignRole($roles[$userData['role']]->name);
        }

        $this->command->info('✅ Usuarios con roles y periodos creados correctamente.');
    }
}
