<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'email',
        'image',
        'type',
        'address',
    ];


    /**
     * Get the image URL or return a default placeholder
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('assets/img/default-avatar.png');
    }

    /**
     * Get all grocery cash transactions for this customer
     */
    public function groceryCashTransactions()
    {
        return $this->hasMany(GroceryCashTransaction::class);
    }

    /**
     * Get all installments for this customer
     */
    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}

