<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoices.show');
});
