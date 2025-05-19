<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all roles
        $customerRole = Role::where('name', 'customer')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $adminRole = Role::where('name', 'admin')->first();

        if (!$customerRole || !$employeeRole || !$adminRole) {
            $this->command->error('Required roles not found. Run roles migration first.');
            return;
        }

        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            // Only assign roles if the user doesn't have any
            $currentRoles = $user->roles()->count();
            
            if ($currentRoles > 0) {
                $this->command->info("User {$user->name} already has roles. Skipping.");
                continue;
            }
            
            // Assign roles based on email or name patterns
            if (stripos($user->email, 'admin') !== false || $user->is_admin) {
                $user->assignRole($adminRole);
                $this->command->info("Assigned Admin role to {$user->name}");
            } 
            else if (stripos($user->email, 'employee') !== false) {
                $user->assignRole($employeeRole);
                $this->command->info("Assigned Employee role to {$user->name}");
            }
            else {
                // Default to customer
                $user->assignRole($customerRole);
                $this->command->info("Assigned Customer role to {$user->name}");
            }
        }
        
        $this->command->info('Role assignment completed successfully!');
    }
} 