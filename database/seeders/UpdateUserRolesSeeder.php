<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UpdateUserRolesSeeder extends Seeder
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

        // Get specific users by email
        $adminUser = User::where('email', 'admin@google.com')->first();
        if ($adminUser) {
            $adminUser->syncRoles([$adminRole]);
            $this->command->info("Assigned Admin role to {$adminUser->name}");
        }
        
        $employeeUser = User::where('email', 'employee@google.com')->first();
        if ($employeeUser) {
            $employeeUser->syncRoles([$employeeRole]);
            $this->command->info("Assigned Employee role to {$employeeUser->name}");
        }
        
        $customerUser = User::where('email', 'customer@google.com')->first();
        if ($customerUser) {
            $customerUser->syncRoles([$customerRole]);
            $this->command->info("Assigned Customer role to {$customerUser->name}");
        }
        
        // Get other users mentioned in the example
        $ahmadSaleh = User::where('email', 'mohamed.saleh@sut.edu.eg')->first();
        if ($ahmadSaleh) {
            $ahmadSaleh->syncRoles([$customerRole]);
            $this->command->info("Assigned Customer role to {$ahmadSaleh->name}");
        }
        
        $ahmadAli = User::where('email', 'malisobh2010@gmail.com')->first();
        if ($ahmadAli) {
            $ahmadAli->syncRoles([$customerRole]);
            $this->command->info("Assigned Customer role to {$ahmadAli->name}");
        }
        
        $naderMohsen = User::where('email', 'nader.mohsen@gmail.com')->first();
        if ($naderMohsen) {
            $naderMohsen->syncRoles([$customerRole]);
            $this->command->info("Assigned Customer role to {$naderMohsen->name}");
        }
        
        // For any missing users, assign default role
        $users = User::whereNotIn('email', [
            'admin@google.com',
            'employee@google.com', 
            'customer@google.com',
            'mohamed.saleh@sut.edu.eg',
            'malisobh2010@gmail.com',
            'nader.mohsen@gmail.com'
        ])->get();
        
        foreach ($users as $user) {
            $user->syncRoles([$customerRole]);
            $this->command->info("Assigned Customer role to {$user->name}");
        }
        
        $this->command->info('Role update completed successfully!');
    }
} 