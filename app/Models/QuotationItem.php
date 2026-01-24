<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'item_name',
        'description',
        'qty',
        'price',
        'total',
        'is_adjustment',
    ];

    protected $casts = [
        'qty'           => 'decimal:2',
        'price'         => 'decimal:2',
        'total'         => 'decimal:2',
        'is_adjustment' => 'boolean',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
