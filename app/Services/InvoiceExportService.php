<?php

namespace App\Services;

use App\Models\Invoice;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceExportService
{
    public function exportCsv(array $filters = []): StreamedResponse
    {
        $query = Invoice::query()->with('client');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate('invoice_date', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('invoice_date', '<=', $filters['to']);
        }

        $filename = 'invoices-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // header
            fputcsv($handle, [
                'Invoice Number',
                'Client',
                'Invoice Date',
                'Status',
                'Subtotal',
                'Discount',
                'Tax',
                'Total',
            ]);

            $query->orderBy('invoice_date')->chunk(200, function ($invoices) use ($handle) {
                foreach ($invoices as $invoice) {
                    fputcsv($handle, [
                        $invoice->invoice_number,
                        $invoice->client->name,
                        $invoice->invoice_date->format('Y-m-d'),
                        $invoice->status,
                        $invoice->subtotal,
                        $invoice->discount,
                        $invoice->tax,
                        $invoice->total,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
