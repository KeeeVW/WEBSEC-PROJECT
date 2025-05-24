<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class TwitterAuthController extends SocialAuthController
{
    public function redirect()
    {
        try {
            return Socialite::driver('twitter')
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Twitter redirect error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to connect to Twitter. Please try again later.');
        }
    }

    public function callback()
    {
        try {
            $twitterUser = Socialite::driver('twitter')->user();
            Log::info('Twitter user data received', ['name' => $twitterUser->name]);
            
            return $this->handleSocialUser($twitterUser, 'twitter');
        } catch (\Exception $e) {
            Log::error('Twitter callback error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to login with Twitter. Please try again later.');
        }
    }
} 