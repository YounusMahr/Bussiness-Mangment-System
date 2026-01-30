<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlotSaleTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_sale_id',
        'date',
        'type',
        'installment_no',
        'installment_amount',
        'paid_amount',
        'payment_amount',
        'total_sale_price_before',
        'paid_before',
        'remaining_before',
        'total_sale_price_after',
        'paid_after',
        'remaining_after',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'installment_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'total_sale_price_before' => 'decimal:2',
        'paid_before' => 'decimal:2',
        'remaining_before' => 'decimal:2',
        'total_sale_price_after' => 'decimal:2',
        'paid_after' => 'decimal:2',
        'remaining_after' => 'decimal:2',
    ];

    public function plotSale()
    {
        return $this->belongsTo(PlotSale::class);
    }
}
