<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->sendError('User not found.', [], 404);
        }

        return $this->sendResponse($user, 'User profile retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->sendError('User not found.', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $user->fill($validator->validated());
        $user->save();

        return $this->sendResponse($user, 'User profile updated successfully.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->sendError('User not found.', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        if (!\Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Incorrect current password.');
        }

        $user->password = \Hash::make($request->new_password);
        $user->save();

        // Optional: Invalidate all other tokens for the user for security
        // $user->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();

        return $this->sendResponse([], 'Password updated successfully.');
    }
} 