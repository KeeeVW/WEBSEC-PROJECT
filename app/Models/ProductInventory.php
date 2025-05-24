<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'location',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<', 10);
    }

    public function scopeInLocation($query, $location)
    {
        return $query->where('location', $location);
    }
} 