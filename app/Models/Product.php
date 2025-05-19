<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model  {

	protected $fillable = [
        'code',
        'name',
        'price',
        'model',
        'description',
        'photo'
    ];
    
    /**
     * Get the product's inventory.
     */
    public function inventory()
    {
        return $this->hasOne(ProductInventory::class);
    }
    
    /**
     * Get the purchases for this product.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Get the users who have favorited this product.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_favorites');
    }
    
    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        if ($this->inventory) {
            return $this->inventory->quantity > 0;
        }
        
        return false;
    }
    
    /**
     * Get the available quantity
     */
    public function getAvailableQuantity()
    {
        if ($this->inventory) {
            return $this->inventory->quantity;
        }
        
        return 0;
    }
}