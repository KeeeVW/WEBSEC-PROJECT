<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up()
    {
        // Create the reports permission if it doesn't exist
        $permission = Permission::firstOrCreate(['name' => 'reports']);

        // Get the manager role
        $managerRole = Role::where('name', 'manager')->first();

        if ($managerRole) {
            // Assign the reports permission to the manager role
            $managerRole->givePermissionTo($permission);
        }
    }

    public function down()
    {
        // Get the manager role
        $managerRole = Role::where('name', 'manager')->first();

        if ($managerRole) {
            // Revoke the reports permission from the manager role
            $managerRole->revokePermissionTo('reports');
        }

        // Delete the reports permission
        Permission::where('name', 'reports')->delete();
    }
}; 