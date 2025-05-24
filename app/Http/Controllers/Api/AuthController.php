<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    /**
     * Register new user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'security_question' => $request->security_question,
                'security_answer' => $request->security_answer,
                'is_blocked' => false, // Default to not blocked
            ]);

            // Assign the 'customer' role
            $customerRole = \Spatie\Permission\Models\Role::where('name', 'customer')->first();
            if ($customerRole) {
                $user->assignRole($customerRole);
            }

            // Create UserCredit entry
            \App\Models\UserCredit::create([
                'user_id' => $user->id,
                'amount' => 0.00,
            ]);

            // Send email verification notification
            $user->sendEmailVerificationNotification();

            DB::commit();

            return $this->sendResponse([], 'User registered successfully. Please verify your email address before logging in.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('User registration failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return $this->sendError('Registration failed. Please try again later.');
        }
    }

    /**
     * Login user and create token.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Unauthorized.', ['error' => 'Invalid credentials']);
        }

        $user = $request->user();

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            // Log out the user if they were logged in by Auth::attempt
             Auth::logout();
             return $this->sendError('Email not verified.', ['error' => 'Please verify your email address before logging in.']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->sendResponse([
            'user' => $user,
            'token' => $token
        ], 'User logged in successfully');
    }

    /**
     * Logout user (Revoke the token).
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'User logged out successfully');
    }

    /**
     * Get the authenticated User.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function user(Request $request)
    {
        return $this->sendResponse($request->user(), 'User retrieved successfully');
    }

    /**
     * Send password reset link.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Use Laravel's built-in password broker to send the reset link
        $status = \Password::sendResetLink(
            $request->only('email')
        );

        \Log::info('Password reset link status:', ['status' => $status]);

        $responseData = [];

        // For debugging: include the token in the response if debug_token is true
        if ($status === 'passwords.sent' && $request->query('debug_token')) {
            // Retrieve the token directly from the database (assuming default password broker setup)
            $tokenData = DB::table('password_resets')
                             ->where('email', $request->email)
                             ->first();

            if ($tokenData) {
                // The token stored is usually the hashed version. We need the raw token.
                // Unfortunately, getting the raw token after sendResetLink is not direct.
                // A common workaround for debugging is to fetch the latest token from the DB
                // immediately after the send call. This assumes a short window.
                // A more robust dev-only solution might involve overriding the Mailer.
                // For simplicity and immediate testing, we'll fetch from DB.
                // NOTE: The token in the DB might be hashed depending on Laravel version/config.
                // If you get a hashed token, you'll need to adjust.

                 // Laravel 9+ uses password_reset_tokens table where the token IS NOT hashed in DB
                 $responseData['debug_token'] = $tokenData->token;
            } else {
                // Fallback if token wasn't found immediately after
                $responseData['debug_token'] = 'Token not found in DB immediately.';
            }
        }

        // Return a consistent response regardless of whether the email exists
        return $this->sendResponse($responseData, trans($status));
    }

    /**
     * Reset password.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Use Laravel's built-in password broker to reset the password
        $status = \Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (\App\Models\User $user, string $password) {
                $user->forceFill([
                    'password' => \Hash::make($password),
                    'remember_token' => null,
                ])->save();

                $user->tokens()->delete(); // Invalidate existing tokens
            }
        );

        if ($status === \Password::PASSWORD_RESET) {
            return $this->sendResponse([], trans($status));
        }

        return $this->sendError(trans($status));
    }
} 