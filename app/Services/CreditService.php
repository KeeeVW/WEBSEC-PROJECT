<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCredit;
use App\Models\CreditTransaction;
use Illuminate\Support\Facades\DB;

class CreditService
{
    /**
     * Add credit to a user
     *
     * @param User $user The user to add credit to
     * @param float $amount The amount to add
     * @param User|null $addedBy The employee who added credit (optional)
     * @param string|null $description Description of the transaction (optional)
     * @return bool Whether the operation was successful
     */
    public function addCredit(User $user, float $amount, ?User $addedBy = null, ?string $description = null): bool
    {
        if ($amount <= 0) {
            \Illuminate\Support\Facades\Log::warning("Attempted to add non-positive amount: {$amount} to user {$user->id}");
            return false;
        }
        
        try {
            DB::beginTransaction();
            
            // Create or update user credit
            $credit = UserCredit::firstOrCreate(['user_id' => $user->id], ['amount' => 0]);
            $oldAmount = $credit->amount;
            $credit->amount = $oldAmount + $amount; // Explicit calculation to avoid floating point issues
            $credit->save();
            
            // Record the transaction
            CreditTransaction::create([
                'user_id' => $user->id,
                'added_by' => $addedBy ? $addedBy->id : null,
                'amount' => $amount,
                'type' => 'add',
                'description' => $description ?: 'Credit added'
            ]);
            
            // Log the transaction
            \Illuminate\Support\Facades\Log::info(
                "Credit added: {$amount} to user {$user->id}" . 
                ($addedBy ? " by {$addedBy->id}" : "") . 
                ". Previous balance: {$oldAmount}, New balance: {$credit->amount}"
            );
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Failed to add credit: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deduct credit from a user
     *
     * @param User $user The user to deduct credit from
     * @param float $amount The amount to deduct
     * @param string|null $description Description of the transaction (optional)
     * @return bool Whether the operation was successful
     */
    public function deductCredit(User $user, float $amount, ?string $description = null): bool
    {
        if ($amount <= 0) {
            return false;
        }
        
        $credit = UserCredit::where('user_id', $user->id)->first();
        
        if (!$credit || $credit->amount < $amount) {
            return false; // Insufficient funds
        }
        
        try {
            DB::beginTransaction();
            
            // Update user credit
            $credit->amount -= $amount;
            $credit->save();
            
            // Record the transaction
            CreditTransaction::create([
                'user_id' => $user->id,
                'amount' => -$amount, // Negative amount for deduction
                'type' => 'purchase',
                'description' => $description ?: 'Credit used for purchase'
            ]);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    
    /**
     * Get user's current credit balance
     *
     * @param User $user The user to check
     * @return float The current balance
     */
    public function getBalance(User $user): float
    {
        $credit = UserCredit::where('user_id', $user->id)->first();
        return $credit ? $credit->amount : 0.00;
    }
    
    /**
     * Check if user has sufficient funds
     *
     * @param User $user The user to check
     * @param float $amount The amount needed
     * @return bool Whether the user has sufficient funds
     */
    public function hasSufficientFunds(User $user, float $amount): bool
    {
        return $this->getBalance($user) >= $amount;
    }
} 