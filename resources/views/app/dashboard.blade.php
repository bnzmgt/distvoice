@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-4">
    <h1 class="text-xl font-semibold text-gray-800">
        Dashboard
    </h1>

    <div class="grid grid-cols-1 gap-4">
        <a href="{{ route('app.invoices.index') }}"
           class="block p-4 bg-white rounded-lg shadow hover:bg-gray-50">
            <div class="font-medium">Invoices</div>
            <div class="text-sm text-gray-500">
                View & manage invoices
            </div>
        </a>

        <a href="{{ route('app.clients.index') }}"
           class="block p-4 bg-white rounded-lg shadow hover:bg-gray-50">
            <div class="font-medium">Clients</div>
            <div class="text-sm text-gray-500">
                Manage client data
            </div>
        </a>

        <a href="{{ route('app.products.index') }}"
           class="block p-4 bg-white rounded-lg shadow hover:bg-gray-50">
            <div class="font-medium">Products</div>
            <div class="text-sm text-gray-500">
                Manage products
            </div>
        </a>
    </div>
</div>
@endsection
