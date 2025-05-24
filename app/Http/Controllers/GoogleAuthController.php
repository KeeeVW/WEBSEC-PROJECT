<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends SocialAuthController
{
    public function redirect()
    {
        try {
            Log::info('Attempting Google redirect');
            return Socialite::driver('google')
                ->scopes(['email', 'profile'])
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Google redirect error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')
                ->with('error', 'Unable to connect to Google. Please try again later.');
        }
    }

    public function callback()
    {
        try {
            Log::info('Received Google callback');
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google user data received', [
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'id' => $googleUser->id
            ]);
            
            return $this->handleSocialUser($googleUser, 'google');
        } catch (\Exception $e) {
            Log::error('Google callback error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again later.');
        }
    }
} 