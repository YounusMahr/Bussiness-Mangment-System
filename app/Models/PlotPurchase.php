<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotPurchase extends Model
{
    protected $fillable = [
        'customer_id',
        'date',
        'plot_area',
        'plot_price',
        'installments',
        'location',
    ];

    protected $casts = [
        'date' => 'date',
        'plot_price' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function plotSales()
    {
        return $this->hasMany(PlotSale::class);
    }
}
