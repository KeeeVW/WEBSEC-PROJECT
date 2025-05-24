<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'is_admin',
        'security_question',
        'security_answer',
        'is_using_temp_password',
        'provider',
        'provider_id',
        'avatar',
        'is_blocked',
        'twitter_id',
        'linkedin_id',
        'google_id',
        'social_avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_answer',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_using_temp_password' => 'boolean',
            'security_answer' => 'hashed',
        ];
    }
    
    /**
     * Get the user's credit.
     */
    public function credit()
    {
        return $this->hasOne(UserCredit::class);
    }
    
    /**
     * Get the user's credit transactions.
     */
    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class);
    }
    
    /**
     * Get the user's purchases.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Get the customers associated with this employee.
     */
    public function customers()
    {
        return $this->belongsToMany(User::class, 'employee_customers', 'employee_id', 'customer_id')
            ->withTimestamps();
    }
    
    /**
     * Get the employees associated with this customer.
     */
    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_customers', 'customer_id', 'employee_id')
            ->withTimestamps();
    }
    
    /**
     * Get the products the user has favorited.
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_favorites');
    }
    
    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->is_admin || $this->hasRole('admin');
    }
    
    /**
     * Check if the user is an employee.
     */
    public function isEmployee()
    {
        return $this->hasRole('employee');
    }
    
    /**
     * Check if the user is a customer.
     */
    public function isCustomer()
    {
        return $this->hasRole('customer');
    }
    
    /**
     * Get the user's credit balance.
     */
    public function getCreditAmount()
    {
        // Force a fresh database query to ensure we have the latest data
        $credit = $this->credit()->first();
        
        // If no credit record exists, create one with zero balance
        if (!$credit) {
            $credit = UserCredit::create([
                'user_id' => $this->id,
                'amount' => 0
            ]);
        }
        
        return $credit->amount;
    }

    /**
     * Check if user can manage a specific customer
     * 
     * @param int $customerId
     * @return bool
     */
    public function managesCustomer($customerId)
    {
        return $this->customers()->where('customer_id', $customerId)->exists();
    }

    
    public function isBlocked()
    {
        return $this->is_blocked;
    }

    /**
     * Block or unblock the user.
     * 
     * @param bool $status
     * @return bool
     */
    public function setBlockedStatus($status)
    {
        $this->is_blocked = $status;
        return $this->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification());
    }

    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();
        $this->save();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(UserCredit::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
}
