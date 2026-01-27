<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\InvoiceNumberGenerator;
use App\Models\Company;

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
            if (empty($invoice->company_id)) {
                $defaultCompany = Company::getDefault();

                if ($defaultCompany) {
                    $invoice->company_id = $defaultCompany->id;
                }
            }

            if (empty($invoice->invoice_number)) {
                $generator = app(InvoiceNumberGenerator::class);
                $invoice->invoice_number = $generator->generate('INV');
            }

            if (empty($invoice->public_token)) {
                $invoice->public_token = Str::uuid()->toString();
            }

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

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
