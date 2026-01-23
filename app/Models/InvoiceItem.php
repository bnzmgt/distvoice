<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
