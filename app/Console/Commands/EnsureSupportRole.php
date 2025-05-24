<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EnsureSupportRole extends Command
{
    protected $signature = 'role:ensure-support';
    protected $description = 'Ensure support role exists with proper permissions';

    public function handle()
    {
        $this->info('Ensuring support role exists...');

        // Create support role if it doesn't exist
        $supportRole = Role::firstOrCreate(['name' => 'support', 'guard_name' => 'web']);
        $this->info('Support role created/verified.');

        // Define support permissions
        $permissions = [
            'view_tickets',
            'respond_tickets',
            'view_customers',
            'view_orders',
            'create_orders'
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $this->info('Support permissions created/verified.');

        // Assign all permissions to support role
        $supportRole->syncPermissions($permissions);
        $this->info('Permissions assigned to support role.');

        $this->info('Support role setup completed successfully!');
    }
} 
 