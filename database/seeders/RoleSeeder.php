<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $teacher = Role::create(['name' => 'teacher']);
        $student = Role::create(['name' => 'student']);

        // Crear permisos
        $permission1 = Permission::create(['name' => 'manage users']);
        $permission2 = Permission::create(['name' => 'manage courses']);

        // Asignar permisos a los roles
        $admin->givePermissionTo([$permission1, $permission2]);
        $teacher->givePermissionTo($permission2);
    }
}
