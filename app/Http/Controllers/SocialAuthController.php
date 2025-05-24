<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SocialAuthController extends Controller
{
    protected function handleSocialUser($socialUser, $provider)
    {
        try {
            DB::beginTransaction();

            // Check if user exists with social ID
            $user = User::where($provider . '_id', $socialUser->id)->first();

            if (!$user) {
                // Generate a temporary email if none is provided
                $email = $socialUser->email ?? $socialUser->id . '@twitter.temp';
                
                // Check if user exists with same email
                $user = User::where('email', $email)->first();

                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->name,
                        'email' => $email,
                        'password' => Hash::make(Str::random(24)),
                        'provider' => $provider,
                        $provider . '_id' => $socialUser->id,
                        'social_avatar' => $socialUser->avatar,
                        'email_verified_at' => now(), // Social logins are pre-verified
                    ]);

                    // Assign customer role
                    $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
                    $user->assignRole($customerRole);

                    // Create user credit record
                    \App\Models\UserCredit::create([
                        'user_id' => $user->id,
                        'amount' => 0.00
                    ]);
                } else {
                    // Update existing user with social ID
                    $user->update([
                        'provider' => $provider,
                        $provider . '_id' => $socialUser->id,
                        'social_avatar' => $socialUser->avatar
                    ]);
                }
            }

            DB::commit();
            Auth::login($user);
            return redirect('/');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($provider . ' login error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to login with ' . ucfirst($provider) . '. Please try again later.');
        }
    }
} 