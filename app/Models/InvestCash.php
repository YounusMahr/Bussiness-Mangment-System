<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestCash extends Model
{
    use HasFactory;

    protected $table = 'invest_cash';

    protected $fillable = [
        'date',
        'customer_name',
        'customer_number',
        'invest_cash',
        'interest',
        'time_period',
        'due_date',
        'return_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'invest_cash' => 'decimal:2',
        'interest' => 'decimal:2',
        'return_amount' => 'decimal:2',
    ];

    public function cashOuts()
    {
        return $this->hasMany(CashOut::class, 'invest_cash_id');
    }
}

