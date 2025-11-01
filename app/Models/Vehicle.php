<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'Vehicle_name',
        'model',
        'status',
        'rent_price',
        'image',
        'description',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'rent_price' => 'decimal:1',
    ];

    public function bookings()
    {
        return $this->hasMany(VehicleBooking::class);
    }
}


