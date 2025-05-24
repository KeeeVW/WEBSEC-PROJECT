<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUserEmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emailUpdates = [
            'admin@example.com' => 'admin@google.com',
            'manager@example.com' => 'manager@google.com',
            'support@example.com' => 'support@google.com',
            'customer@example.com' => 'customer@google.com',
            'employee@example.com' => 'employee@google.com',
        ];

        foreach ($emailUpdates as $oldEmail => $newEmail) {
            $user = User::where('email', $oldEmail)->first();
            if ($user) {
                $user->email = $newEmail;
                $user->save();
            }
        }
    }
} 