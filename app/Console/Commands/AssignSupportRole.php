<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AssignSupportRole extends Command
{
    protected $signature = 'role:assign-support {email}';
    protected $description = 'Assign support role to a user by email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $user->assignRole('support');
        $this->info("Support role assigned to user {$email} successfully!");
        
        return 0;
    }
} 