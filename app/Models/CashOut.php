<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOut extends Model
{
    use HasFactory;

    protected $table = 'cash_out';

    protected $fillable = [
        'date',
        'invest_cash_id',
        'available_balance',
        'returned_amount',
        'remaining_balance',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'available_balance' => 'decimal:2',
        'returned_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];

    public function investCash()
    {
        return $this->belongsTo(InvestCash::class, 'invest_cash_id');
    }
}

