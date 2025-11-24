<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchaseTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_purchase_id',
        'date',
        'type',
        'new_goods_name',
        'new_goods_total_price',
        'new_paid',
        'new_interest',
        'new_total_stock',
        'new_given_stock',
        'return_stock',
        'return_payment',
        'total_stock_before',
        'remaining_stock_before',
        'goods_total_price_before',
        'paid_before',
        'remaining_before',
        'interest_before',
        'total_remaining_before',
        'total_stock_after',
        'remaining_stock_after',
        'goods_total_price_after',
        'paid_after',
        'remaining_after',
        'interest_after',
        'total_remaining_after',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'new_goods_total_price' => 'decimal:2',
        'new_paid' => 'decimal:2',
        'new_interest' => 'decimal:2',
        'new_total_stock' => 'decimal:2',
        'new_given_stock' => 'decimal:2',
        'return_stock' => 'decimal:2',
        'return_payment' => 'decimal:2',
        'total_stock_before' => 'decimal:2',
        'remaining_stock_before' => 'decimal:2',
        'goods_total_price_before' => 'decimal:2',
        'paid_before' => 'decimal:2',
        'remaining_before' => 'decimal:2',
        'interest_before' => 'decimal:2',
        'total_remaining_before' => 'decimal:2',
        'total_stock_after' => 'decimal:2',
        'remaining_stock_after' => 'decimal:2',
        'goods_total_price_after' => 'decimal:2',
        'paid_after' => 'decimal:2',
        'remaining_after' => 'decimal:2',
        'interest_after' => 'decimal:2',
        'total_remaining_after' => 'decimal:2',
    ];

    public function stockPurchase()
    {
        return $this->belongsTo(StockPurchase::class);
    }
}
