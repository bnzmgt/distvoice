<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->paginate(10);
        return view('app.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        return view('app.invoices.show', compact('invoices'));
    }
}
