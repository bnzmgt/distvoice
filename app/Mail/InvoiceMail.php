<?php

namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice)
    {
        $this->invoice->load(['client', 'items.product']);
    }

    public function build()
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $this->invoice,
        ]);

        return $this->subject('Invoice ' . $this->invoice->invoice_number)
            ->view('emails.invoice')
            ->attachData(
                $pdf->output(),
                'invoice-' . $this->invoice->invoice_number . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
