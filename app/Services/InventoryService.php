<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Update a product's inventory
     *
     * @param Product $product The product to update
     * @param int $quantity The new quantity
     * @return bool Whether the operation was successful
     */
    public function updateInventory(Product $product, int $quantity): bool
    {
        try {
            $inventory = ProductInventory::firstOrCreate(['product_id' => $product->id], ['quantity' => 0]);
            $inventory->quantity = max(0, $quantity); // Ensure we don't have negative inventory
            $inventory->save();
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Add to a product's inventory
     *
     * @param Product $product The product to update
     * @param int $quantity The quantity to add
     * @return bool Whether the operation was successful
     */
    public function addToInventory(Product $product, int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }
        
        try {
            $inventory = ProductInventory::firstOrCreate(['product_id' => $product->id], ['quantity' => 0]);
            $inventory->quantity += $quantity;
            $inventory->save();
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Remove from a product's inventory
     *
     * @param Product $product The product to update
     * @param int $quantity The quantity to remove
     * @return bool Whether the operation was successful
     */
    public function removeFromInventory(Product $product, int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }
        
        $inventory = ProductInventory::where('product_id', $product->id)->first();
        
        if (!$inventory || $inventory->quantity < $quantity) {
            return false; // Not enough inventory
        }
        
        try {
            $inventory->quantity -= $quantity;
            $inventory->save();
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Get a product's current inventory
     *
     * @param Product $product The product to check
     * @return int The current inventory quantity
     */
    public function getInventory(Product $product): int
    {
        $inventory = ProductInventory::where('product_id', $product->id)->first();
        return $inventory ? $inventory->quantity : 0;
    }
    
    /**
     * Check if a product is in stock
     *
     * @param Product $product The product to check
     * @param int $quantity The quantity needed (default: 1)
     * @return bool Whether the product is in stock
     */
    public function isInStock(Product $product, int $quantity = 1): bool
    {
        return $this->getInventory($product) >= $quantity;
    }
} 