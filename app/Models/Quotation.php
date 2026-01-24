<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number',
        'client_id',
        'quotation_date',
        'expired_date',
        'subtotal',
        'discount',
        'total',
        'notes',
        'status',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'expired_date'   => 'date',
        'subtotal'       => 'decimal:2',
        'discount'       => 'decimal:2',
        'total'          => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
