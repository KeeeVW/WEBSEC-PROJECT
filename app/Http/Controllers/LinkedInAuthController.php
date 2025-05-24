<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LinkedInAuthController extends SocialAuthController
{
    private const LINKEDIN_STATUS_CHECK_INTERVAL = 5; // minutes
    private const LINKEDIN_API_URL = 'https://www.linkedin.com';

    public function redirect()
    {
        try {
            // Check LinkedIn status before attempting redirect
            if (!$this->checkLinkedInStatus()) {
                return redirect()->route('login')
                    ->with('error', 'LinkedIn service is currently experiencing issues. Please try again in a few minutes or use another login method (GitHub, Google, etc.).');
            }

            return Socialite::driver('linkedin')
                ->scopes(['r_liteprofile', 'r_emailaddress'])
                ->redirect();
        } catch (\Exception $e) {
            Log::error('LinkedIn redirect error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->markLinkedInAsUnavailable();
            return $this->handleLinkedInError($e);
        }
    }

    public function callback()
    {
        try {
            // Check LinkedIn status before processing callback
            if (!$this->checkLinkedInStatus()) {
                return redirect()->route('login')
                    ->with('error', 'LinkedIn service is currently experiencing issues. Please try again in a few minutes or use another login method (GitHub, Google, etc.).');
            }

            $linkedinUser = Socialite::driver('linkedin')->user();
            Log::info('LinkedIn user data received', [
                'email' => $linkedinUser->email,
                'name' => $linkedinUser->name,
                'id' => $linkedinUser->id
            ]);
            
            $this->markLinkedInAsAvailable();
            return $this->handleSocialUser($linkedinUser, 'linkedin');
        } catch (\Exception $e) {
            Log::error('LinkedIn callback error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->markLinkedInAsUnavailable();
            return $this->handleLinkedInError($e);
        }
    }

    /**
     * Check if LinkedIn is available by making a HEAD request
     */
    private function checkLinkedInStatus()
    {
        // Check cache first
        if ($this->isLinkedInUnavailable()) {
            return false;
        }

        try {
            $response = Http::timeout(5)->head(self::LINKEDIN_API_URL);
            $isAvailable = $response->successful();
            
            if (!$isAvailable) {
                $this->markLinkedInAsUnavailable();
            }
            
            return $isAvailable;
        } catch (\Exception $e) {
            Log::warning('LinkedIn status check failed: ' . $e->getMessage());
            $this->markLinkedInAsUnavailable();
            return false;
        }
    }

    /**
     * Handle LinkedIn errors with appropriate user feedback
     */
    private function handleLinkedInError(\Exception $e)
    {
        $errorMessage = 'Unable to connect to LinkedIn. ';
        
        if (str_contains($e->getMessage(), 'Could not resolve host') || 
            str_contains($e->getMessage(), 'Connection timed out')) {
            $errorMessage .= 'The service might be temporarily unavailable. ';
        }
        
        $errorMessage .= 'Please try again in a few minutes or use another login method (GitHub, Google, etc.).';
        
        return redirect()->route('login')
            ->with('error', $errorMessage)
            ->with('linkedin_unavailable', true);
    }

    /**
     * Check if LinkedIn is marked as unavailable
     */
    private function isLinkedInUnavailable()
    {
        return Cache::get('linkedin_unavailable', false);
    }

    /**
     * Mark LinkedIn as unavailable for the specified interval
     */
    private function markLinkedInAsUnavailable()
    {
        Cache::put('linkedin_unavailable', true, now()->addMinutes(self::LINKEDIN_STATUS_CHECK_INTERVAL));
    }

    /**
     * Mark LinkedIn as available
     */
    private function markLinkedInAsAvailable()
    {
        Cache::forget('linkedin_unavailable');
    }
} 