@extends('layouts.app')

@section('content')
    <h1>Invoice {{ $invoice->invoice_number }}</h1>

    <p>Client: {{ $invoice->client->name }}</p>
    <p>Status: {{ $invoice->status }}</p>

    <hr>

    @foreach ($invoice->items as $item)
        <div>
            {{ $item->item_name }} —
            {{ $item->qty }} x {{ number_format($item->price, 2) }}
        </div>
    @endforeach

    <hr>

    <strong>Total: {{ number_format($invoice->total, 2) }}</strong>
@endsection
