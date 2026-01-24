<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 24px; }
        h1 { margin-bottom: 8px; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; }
        .right { text-align: right; }
        .no-border td { border: none; padding: 4px 0; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; background: #eee; }
        .paid { background: #d1fae5; }
        .sent { background: #fef3c7; }
        .draft { background: #e5e7eb; }
        .cancelled { background: #fee2e2; }
        footer { margin-top: 24px; font-size: 12px; color: #666; }
    </style>
</head>
<body>

<h1>Invoice {{ $invoice->invoice_number }}</h1>
<div class="muted">
    <span class="badge {{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
</div>

<table class="no-border">
    <tr>
        <td>
            <strong>Client</strong><br>
            {{ $invoice->client->name }}<br>
            {{ $invoice->client->company_name }}
        </td>
        <td class="right">
            <strong>Date</strong>: {{ $invoice->invoice_date->format('d M Y') }}<br>
            @if($invoice->due_date)
                <strong>Due</strong>: {{ $invoice->due_date->format('d M Y') }}
            @endif
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th class="right">Qty</th>
            <th class="right">Price</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice->items as $item)
            <tr>
                <td>{{ $item->item_name }}</td>
                <td class="right">{{ $item->qty }}</td>
                <td class="right">{{ number_format($item->price, 2) }}</td>
                <td class="right">{{ number_format($item->qty * $item->price, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="no-border" style="margin-top: 12px;">
    <tr>
        <td class="right" width="80%">Subtotal</td>
        <td class="right">{{ number_format($invoice->subtotal, 2) }}</td>
    </tr>
    <tr>
        <td class="right">Discount</td>
        <td class="right">{{ number_format($invoice->discount, 2) }}</td>
    </tr>
    <tr>
        <td class="right">Tax</td>
        <td class="right">{{ number_format($invoice->tax, 2) }}</td>
    </tr>
    <tr>
        <td class="right"><strong>Total</strong></td>
        <td class="right"><strong>{{ number_format($invoice->total, 2) }}</strong></td>
    </tr>
</table>

@if ($invoice->payment_note)
    <p><strong>Note:</strong><br>{{ $invoice->payment_note }}</p>
@endif

<footer>
    <p>This invoice is read-only.</p>
</footer>

</body>
</html>
