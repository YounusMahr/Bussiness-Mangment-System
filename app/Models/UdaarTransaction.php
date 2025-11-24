<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UdaarTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'udaar_id',
        'date',
        'type',
        'new_udaar_amount',
        'interest_amount',
        'product_id',
        'time_period',
        'due_date',
        'payment_amount',
        'paid_amount_before',
        'remaining_amount_before',
        'paid_amount_after',
        'remaining_amount_after',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'new_udaar_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'paid_amount_before' => 'decimal:2',
        'remaining_amount_before' => 'decimal:2',
        'paid_amount_after' => 'decimal:2',
        'remaining_amount_after' => 'decimal:2',
    ];

    public function udaar()
    {
        return $this->belongsTo(Udaar::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
