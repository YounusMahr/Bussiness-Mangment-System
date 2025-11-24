<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_id',
        'date',
        'type',
        'new_car_price',
        'new_interest',
        'new_paid',
        'new_total_price',
        'return_payment',
        'car_price_before',
        'paid_before',
        'remaining_before',
        'interest_before',
        'total_price_before',
        'car_price_after',
        'paid_after',
        'remaining_after',
        'interest_after',
        'total_price_after',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'new_car_price' => 'decimal:2',
        'new_interest' => 'decimal:2',
        'new_paid' => 'decimal:2',
        'new_total_price' => 'decimal:2',
        'return_payment' => 'decimal:2',
        'car_price_before' => 'decimal:2',
        'paid_before' => 'decimal:2',
        'remaining_before' => 'decimal:2',
        'interest_before' => 'decimal:2',
        'total_price_before' => 'decimal:2',
        'car_price_after' => 'decimal:2',
        'paid_after' => 'decimal:2',
        'remaining_after' => 'decimal:2',
        'interest_after' => 'decimal:2',
        'total_price_after' => 'decimal:2',
    ];

    /**
     * Get the installment that owns the transaction
     */
    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}
