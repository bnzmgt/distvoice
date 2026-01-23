<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'type',
        'description',
        'default_price',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
