<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'quantity',
        'price',
        'is_active',
        'meta',
        'published_at',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
