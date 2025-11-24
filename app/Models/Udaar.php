<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Udaar extends Model
{
    protected $fillable = [
        'buy_date',
        'customer_name',
        'customer_number',
        'paid_amount',
        'remaining_amount',
        'interest_amount',
        'due_date',
        'time_period',
        'product_id',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(UdaarTransaction::class)->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }

    protected $casts = [
        'buy_date' => 'date',
        'due_date' => 'date',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
    ];
}
