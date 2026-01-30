<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlotPurchaseTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_purchase_id',
        'date',
        'type',
        'installment_no',
        'installment_amount',
        'paid_amount',
        'payment_amount',
        'plot_price_before',
        'plot_price_after',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'installment_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'plot_price_before' => 'decimal:2',
        'plot_price_after' => 'decimal:2',
    ];

    public function plotPurchase()
    {
        return $this->belongsTo(PlotPurchase::class);
    }
}
