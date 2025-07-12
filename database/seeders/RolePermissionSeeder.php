<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cached roles/permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::firstOrCreate(['name' => 'mark attendance']);
        Permission::firstOrCreate(['name' => 'assign tasks']);
        Permission::firstOrCreate(['name' => 'approve leave']);

        // Create roles
        $student = Role::firstOrCreate(['name' => 'Student']);
        $teacher = Role::firstOrCreate(['name' => 'Teacher']);
        $hr      = Role::firstOrCreate(['name' => 'HR']);

        // Assign permissions to roles
        $student->givePermissionTo('mark attendance');
        $teacher->givePermissionTo('assign tasks');
        $hr->givePermissionTo('approve leave');
    }
}
