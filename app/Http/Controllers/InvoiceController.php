<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);

        return view('invoices.show', compact('invoice'));
    }
}
