<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected $creditService;
    protected $inventoryService;
    
    /**
     * Constructor
     */
    public function __construct(CreditService $creditService, InventoryService $inventoryService)
    {
        $this->creditService = $creditService;
        $this->inventoryService = $inventoryService;
    }
    
    /**
     * Purchase a product
     *
     * @param User $user The user making the purchase
     * @param Product $product The product being purchased
     * @param int $quantity The quantity being purchased (default: 1)
     * @return array Status of the purchase operation
     */
    public function purchaseProduct(User $user, Product $product, int $quantity = 1): array
    {
        // Validate inputs
        if ($quantity <= 0) {
            return [
                'success' => false,
                'message' => 'Invalid quantity'
            ];
        }
        
        // Check if user is logged in and is a customer
        if (!$user->isCustomer()) {
            return [
                'success' => false,
                'message' => 'Only customers can make purchases'
            ];
        }
        
        // Calculate total cost
        $totalCost = $product->price * $quantity;
        
        // Check if user has sufficient funds
        if (!$this->creditService->hasSufficientFunds($user, $totalCost)) {
            return [
                'success' => false,
                'message' => 'Insufficient funds'
            ];
        }
        
        // Check if product is in stock
        if (!$this->inventoryService->isInStock($product, $quantity)) {
            return [
                'success' => false,
                'message' => 'Product is out of stock or not enough quantity available'
            ];
        }
        
        // Process purchase
        try {
            DB::beginTransaction();
            
            // Deduct credit
            $creditDeducted = $this->creditService->deductCredit(
                $user, 
                $totalCost, 
                "Purchase of {$quantity} x {$product->name}"
            );
            
            if (!$creditDeducted) {
                throw new \Exception('Failed to deduct credit');
            }
            
            // Reduce inventory
            $inventoryUpdated = $this->inventoryService->removeFromInventory($product, $quantity);
            
            if (!$inventoryUpdated) {
                throw new \Exception('Failed to update inventory');
            }
            
            // Create purchase record
            Purchase::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'price_paid' => $totalCost,
                'quantity' => $quantity
            ]);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => "Successfully purchased {$quantity} x {$product->name}",
                'total_cost' => $totalCost,
                'new_balance' => $this->creditService->getBalance($user)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Purchase failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get user's purchase history
     *
     * @param User $user The user to get purchases for
     * @return \Illuminate\Database\Eloquent\Collection User's purchases
     */
    public function getUserPurchases(User $user)
    {
        return Purchase::with('product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
} 