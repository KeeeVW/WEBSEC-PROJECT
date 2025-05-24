<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'employee', 'customer', 'manager', 'support'];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $email = strtolower($roleName) . '.google.com';
                
                // Special case for admin email
                if ($roleName === 'admin') {
                    $email = 'admin@google.com';
                }
            
                $password = 'password';

                // Check if a user with this email already exists
                $user = User::where('email', $email)->first();

                if (!$user) {
                    // Create the user
                    $user = User::create([
                        'name' => ucfirst($roleName) . ' User',
                        'email' => $email,
                        'password' => Hash::make($password),
                        'email_verified_at' => now(), // Mark email as verified for seeder users
                    ]);

                    // Assign the role to the user
                    $user->assignRole($role);

                    $this->command->info('Created ' . ucfirst($roleName) . ' user: ' . $email);
                } else {
                    $this->command->info(ucfirst($roleName) . ' user already exists: ' . $email);
                     // Ensure the role is assigned if the user already existed
                    if (!$user->hasRole($role)) {
                         $user->assignRole($role);
                         $this->command->info('Assigned ' . ucfirst($roleName) . ' role to existing user: ' . $email);
                    }
                }
            }
        }

        // Create Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@google.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        // Create Employee user
        $employee = User::create([
            'name' => 'Employee User',
            'email' => 'employee@google.com',
            'password' => Hash::make('password'),
        ]);
        $employee->assignRole('Employee');

        // Create Customer user
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@google.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');

        // Create Manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@google.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('manager');

        // Create Support user
        $support = User::create([
            'name' => 'Support User',
            'email' => 'support@google.com',
            'password' => Hash::make('password'),
        ]);
        $support->assignRole('support');
    }
}
