<?php

namespace App\Http\Controllers\Api;

use App\Models\UserCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CreditController extends BaseController
{
    /**
     * Get authenticated user's credit balance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {
        $user = $request->user();

        // Ensure the user has a UserCredit record
        if (!$user->credit) {
            // This should ideally not happen if a UserCredit is created on registration
            return $this->sendError('Credit information not found.', [], 404);
        }

        return $this->sendResponse([
            'balance' => $user->credit->amount
        ], 'Credit balance retrieved successfully.');
    }

    /**
     * Get authenticated user's credit transaction history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactions(Request $request)
    {
        $user = $request->user();

        // Ensure the user has a UserCredit record
        if (!$user->credit) {
            return $this->sendError('Credit information not found.', [], 404);
        }

        // Assuming UserCredit model has a 'transactions' relationship
        $transactions = $user->credit->transactions()->get();

        return $this->sendResponse($transactions, 'Credit transactions retrieved successfully.');
    }

    /**
     * Add credits to user account.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:credit_card,bank_transfer',
            'payment_details' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            // TODO: Implement payment processing
            // For now, we'll just add the credits directly

            $credit = $request->user()->credits()->create([
                'amount' => $request->amount,
                'type' => 'credit',
                'description' => 'Credit purchase',
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
            ]);

            return $this->sendResponse($credit, 'Credits added successfully');

        } catch (\Exception $e) {
            return $this->sendError('Error adding credits', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get credit transaction details.
     *
     * @param  UserCredit  $credit
     * @return JsonResponse
     */
    public function show(UserCredit $credit)
    {
        if ($credit->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized', [], 403);
        }

        return $this->sendResponse($credit, 'Credit transaction retrieved successfully');
    }
} 