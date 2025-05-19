<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCustomer extends Model
{
    protected $fillable = [
        'employee_id',
        'customer_id'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
} 