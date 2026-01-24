<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'paid_at',
        'amount',
        'method',
        'reference',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount'  => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted()
{
    static::saved(function (Payment $payment) {

        $invoice = $payment->invoice()->with('payments')->first();

        if (! $invoice) {
            return;
        }

        $paid  = (float) $invoice->payments->sum('amount');
        $total = (float) $invoice->total;

        // Jika ada pembayaran, invoice minimal SENT
        if ($paid > 0 && $invoice->status === 'draft') {
            $invoice->updateQuietly(['status' => 'sent']);
        }

        // Partial payment
        if ($paid > 0 && $paid < $total) {
            $invoice->updateQuietly(['status' => 'partial']);
        }

        // Fully paid
        if ($paid >= $total && $total > 0) {
            $invoice->updateQuietly(['status' => 'paid']);
        }
    });
}

}
