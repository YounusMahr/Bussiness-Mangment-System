<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotSale extends Model
{
    protected $fillable = [
        'date',
        'plot_purchase_id',
        'customer_name',
        'customer_number',
        'installments',
        'interest',
        'total_sale_price',
        'paid',
        'remaining',
        'time_period',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'interest' => 'decimal:2',
        'total_sale_price' => 'decimal:2',
        'paid' => 'decimal:2',
        'remaining' => 'decimal:2',
    ];

    public function plotPurchase()
    {
        return $this->belongsTo(PlotPurchase::class);
    }

    public function transactions()
    {
        return $this->hasMany(PlotSaleTransaction::class)->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }
}
