<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id', 'date', 'customer_name', 'total_price', 'discount', 'paid_amount', 'payment_method', 'notes', 'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'total_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function saleItems() {
        return $this->hasMany(SaleItem::class);
    }
}
