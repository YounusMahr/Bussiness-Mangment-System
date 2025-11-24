<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'goods_name',
        'seller_name',
        'contact',
        'goods_total_price',
        'paid',
        'remaining',
        'interest',
        'total_remaining',
        'total_stock',
        'given_stock',
        'remaining_stock',
        'time_period',
        'due_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'goods_total_price' => 'decimal:2',
        'paid' => 'decimal:2',
        'remaining' => 'decimal:2',
        'interest' => 'decimal:2',
        'total_remaining' => 'decimal:2',
        'total_stock' => 'decimal:2',
        'given_stock' => 'decimal:2',
        'remaining_stock' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(StockPurchaseTransaction::class)->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }
}
