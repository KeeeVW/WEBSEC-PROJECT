<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class CleanupRolesSeeder extends Seeder
{
    /**
     * Run the database seeds to clean up roles.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Remove the 'user' role if it exists
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            // Get all users with only user role
            $userRoleUsers = User::role('user')->get();
            
            // Assign customer role to those users
            $customerRole = Role::firstOrCreate(['name' => 'customer']);
            foreach ($userRoleUsers as $user) {
                $user->removeRole('user');
                $user->assignRole('customer');
            }
            
            // Delete user role
            $userRole->delete();
            $this->command->info('Deleted user role and converted users to customers');
        }

        // 2. Update role colors in database
        try {
            // Add a 'color' column to roles table if it doesn't exist
            if (!Schema::hasColumn('roles', 'color')) {
                Schema::table('roles', function($table) {
                    $table->string('color')->nullable()->after('name');
                });
                $this->command->info('Added color column to roles table');
            }
            
            // Set colors for the roles
            $customerRole = Role::firstOrCreate(['name' => 'customer']);
            $customerRole->color = 'green';
            $customerRole->save();
            
            $employeeRole = Role::firstOrCreate(['name' => 'employee']);
            $employeeRole->color = 'yellow';
            $employeeRole->save();
            
            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            $adminRole->color = 'red';
            $adminRole->save();
            
            $this->command->info('Updated role colors successfully');
        } catch (\Exception $e) {
            $this->command->error('Failed to update role colors: ' . $e->getMessage());
            // Continue execution even if this fails
        }

        // 3. Make sure all roles have guard_name = 'web'
        Role::whereNull('guard_name')->update(['guard_name' => 'web']);
        Role::where('guard_name', '!=', 'web')->update(['guard_name' => 'web']);
        
        $this->command->info('Roles cleanup completed successfully');
    }
} 