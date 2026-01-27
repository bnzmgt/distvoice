@extends('layouts.app')

@section('content')
    <h1>Invoices</h1>

    @foreach ($invoices as $invoice)
        <div>
            <a href="{{ route('invoices.show', $invoice) }}">
                {{ $invoice->invoice_number }} — {{ $invoice->client->name }}
            </a>
        </div>
    @endforeach

    {{ $invoices->links() }}
@endsection
