<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MissingPermissionsSeeder extends Seeder
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
        
        // Define permissions to create
        $permissions = [
            'view_users',
            'view_customers',
            'manage_customers'
        ];
        
        $created = [];
        
        // Create permissions if they don't exist
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            $created[] = $permissionName;
        }
        
        // Get the employee role
        $employeeRole = Role::where('name', 'employee')->first();
        
        if ($employeeRole) {
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $employeeRole->givePermissionTo($permission);
                }
            }
            
            $this->command->info('Permissions added to employee role: ' . implode(', ', $created));
        } else {
            $this->command->warn('Employee role not found! Permissions created but not assigned.');
        }
    }
} 