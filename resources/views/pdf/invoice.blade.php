<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h1 { font-size: 20px; margin-bottom: 10px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        table th {
            background: #f5f5f5;
        }
        .text-right { text-align: right; }
        .no-border td {
            border: none;
        }
    </style>
</head>
<body>

    <h1>Invoice {{ $invoice->invoice_number }}</h1>

    <table class="no-border">
        <tr>
            <td>
                <strong>Client</strong><br>
                {{ $invoice->client->name }}<br>
                {{ $invoice->client->company_name }}
            </td>
            <td class="text-right">
                <strong>Date</strong>: {{ $invoice->invoice_date->format('d M Y') }}<br>
                <strong>Status</strong>: {{ strtoupper($invoice->status) }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->qty * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="no-border" style="margin-top: 10px;">
        <tr>
            <td class="text-right" width="80%">Subtotal</td>
            <td class="text-right">{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">Discount</td>
            <td class="text-right">{{ number_format($invoice->discount, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">Tax</td>
            <td class="text-right">{{ number_format($invoice->tax, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Total</strong></td>
            <td class="text-right"><strong>{{ number_format($invoice->total, 2) }}</strong></td>
        </tr>
    </table>

    @if ($invoice->payment_note)
        <p><strong>Note:</strong><br>{{ $invoice->payment_note }}</p>
    @endif

</body>
</html>
