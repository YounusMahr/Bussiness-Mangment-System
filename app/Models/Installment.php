<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'customer_id',
        'number',
        'vehicle',
        'model',
        'installment',
        'car_price',
        'paid',
        'remaining',
        'interest',
        'total_price',
        'note',
        'time_period',
        'due_date',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'car_price' => 'decimal:2',
        'paid' => 'decimal:2',
        'remaining' => 'decimal:2',
        'interest' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the installment
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all transactions for this installment
     */
    public function transactions()
    {
        return $this->hasMany(InstallmentTransaction::class)->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }
}
