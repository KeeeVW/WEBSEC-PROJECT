<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManagerPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create the manage_customers permission
        Permission::firstOrCreate(['name' => 'manage_customers']);

        // Get the manager role
        $managerRole = Role::where('name', 'manager')->first();

        if ($managerRole) {
            // Assign the permission to the manager role
            $managerRole->givePermissionTo('manage_customers');
        }
    }
} 