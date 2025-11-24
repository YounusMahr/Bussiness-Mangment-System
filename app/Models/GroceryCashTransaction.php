<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroceryCashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'customer_id',
        'type',
        'invest_cash',
        'interest',
        'time_period',
        'due_date',
        'return_amount',
        'available_balance',
        'returned_amount',
        'remaining_balance',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'invest_cash' => 'decimal:2',
        'interest' => 'decimal:2',
        'return_amount' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'returned_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get total amount for cash-in transactions
     */
    public function getTotalAmountAttribute()
    {
        if ($this->type === 'cash-in') {
            return $this->invest_cash + $this->interest;
        }
        return $this->returned_amount;
    }
}

