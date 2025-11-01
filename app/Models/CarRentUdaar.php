<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentUdaar extends Model
{
    use HasFactory;

    protected $table = 'car_rent_udaars';

    protected $fillable = [
        'date',
        'booking_id',
        'customer',
        'total_amount',
        'paid_amount',
        'udaar_amount',
        'status',
        'due_date',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'udaar_amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(VehicleBooking::class, 'booking_id');
    }
}

