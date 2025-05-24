<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@google.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@google.com',
                'role' => 'manager',
            ],
            [
                'name' => 'Support User',
                'email' => 'support@google.com',
                'role' => 'support',
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@google.com',
                'role' => 'customer',
            ],
            [
                'name' => 'Employee User',
                'email' => 'employee@google.com',
                'role' => 'employee',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);
        }
    }
} 