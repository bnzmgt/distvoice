<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product', 'company']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
        ])->setPaper('a4');

        $filename = 'invoice-' . str_replace('/', '-', $invoice->invoice_number) . '.pdf';

        return $pdf->download($filename);
    }
}
