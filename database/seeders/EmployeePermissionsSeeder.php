<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class EmployeePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get the employee role
        $employeeRole = Role::where('name', 'employee')->first();

        if (!$employeeRole) {
            $this->command->info('Employee role not found. Creating it.');
            $employeeRole = Role::create(['name' => 'employee']);
        }

        // Define permissions for employees
        $permissions = [
            'view_credits',
            'add_credits',
            'view_customers',
            'view_products',
            'add_products',
            'edit_products',
            'delete_products',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $employeeRole->givePermissionTo($permission);
        }

        // Reset cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Employee permissions assigned:');
        foreach ($permissions as $permissionName) {
            $this->command->info('- ' . $permissionName);
        }
    }
} 