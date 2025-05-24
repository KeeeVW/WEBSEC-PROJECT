<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCredit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'payment_method',
        'payment_details',
    ];

    protected $casts = [
        'payment_details' => 'array',
    ];

    /**
     * Get the user that owns the credit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 