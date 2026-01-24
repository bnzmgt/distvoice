<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'client_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'payment_note',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax'          => 'decimal:2',
        'total'        => 'decimal:2',
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
        return $this->hasMany(InvoiceItem::class);
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->qty * $item->price;
        });

        $discount = $this->discount ?? 0;
        $tax = $this->tax ?? 0;

        $this->subtotal = $subtotal;
        $this->total = max(($subtotal - $discount) + $tax, 0);
    }

    protected static function booted()
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->public_token)) {
                $invoice->public_token = Str::uuid()->toString();
            }
        });

        static::saved(function (Invoice $invoice) {
            $subtotal = $invoice->items()
                ->select(DB::raw('SUM(qty * price) as subtotal'))
                ->value('subtotal') ?? 0;

            $discount = $invoice->discount ?? 0;
            $tax = $invoice->tax ?? 0;

            $total = max(($subtotal - $discount) + $tax, 0);

            // Hindari infinite loop
            $invoice->updateQuietly([
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        });
    }

}
