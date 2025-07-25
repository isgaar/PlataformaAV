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
        // Obtener el rol admin
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error("❌ Error: Asegúrate de ejecutar el RoleSeeder antes de UserSeeder.");
            return;
        }

        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Admin User',
            'last_name' => 'Admin',
            'second_last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'school_id' => null,
            'grade_id' => null,
            'group_id' => null,
            'turno_id' => null,
            'period_id' => null,
        ]);

        // Asignar rol
        $admin->assignRole($adminRole);

        $this->command->info('✅ Usuario administrador creado correctamente.');
    }
}
