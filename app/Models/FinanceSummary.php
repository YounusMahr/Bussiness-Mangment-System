<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceSummary extends Model
{
    protected $fillable = [
        'summary_date',
        'system_type',
        'grocery_revenue',
        'grocery_sales_count',
        'grocery_udhaar',
        'grocery_products_count',
        'car_installment_revenue',
        'car_installment_sales_count',
        'car_installment_remaining',
        'total_revenue',
        'total_sales',
        'total_udhaar',
        'total_customers',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'grocery_revenue' => 'decimal:2',
        'grocery_udhaar' => 'decimal:2',
        'car_installment_revenue' => 'decimal:2',
        'car_installment_remaining' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'total_udhaar' => 'decimal:2',
    ];
}
