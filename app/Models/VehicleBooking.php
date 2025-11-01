<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'vehicle_id',
        'price',
        'customer_name',
        'customer_number',
        'rent_days',
        'total_price',
        'return_date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'return_date' => 'date',
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'rent_days' => 'integer',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function udaars()
    {
        return $this->hasMany(CarRentUdaar::class, 'booking_id');
    }
}

