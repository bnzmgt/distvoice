<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class PublicInvoiceController extends Controller
{
    public function show(string $token)
    {
        $invoice = Invoice::where('public_token', $token)
            ->with(['client', 'items.product'])
            ->firstOrFail();

        return view('public.invoice', compact('invoice'));
    }
}
