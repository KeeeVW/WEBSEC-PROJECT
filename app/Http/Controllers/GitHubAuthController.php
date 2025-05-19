<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class GitHubAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        try {
            return Socialite::driver('github')
                ->scopes(['read:user', 'user:email'])
                ->redirect();
        } catch (\Exception $e) {
            Log::error('GitHub redirect error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to connect to GitHub. Please try again later.');
        }
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            Log::info('GitHub user data received', ['email' => $githubUser->email]);

            DB::beginTransaction();

            // Check if user exists with GitHub ID
            $user = User::where('github_id', $githubUser->id)->first();

            if (!$user) {
                // Check if user exists with same email
                $user = User::where('email', $githubUser->email)->first();

                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $githubUser->name ?? $githubUser->nickname,
                        'email' => $githubUser->email,
                        'github_id' => $githubUser->id,
                        'password' => Hash::make(Str::random(24)),
                    ]);

                    // Assign Customer role to newly registered users
                    $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
                    $user->assignRole($customerRole);
                } else {
                    // Update existing user with GitHub ID
                    $user->github_id = $githubUser->id;
                    $user->save();
                }
            }

            DB::commit();

            // Login the user
            Auth::login($user);

            return redirect()->route('home')
                ->with('success', 'Successfully logged in with GitHub!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GitHub callback error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Authentication failed. Please try again or use another method.');
        }
    }
} 