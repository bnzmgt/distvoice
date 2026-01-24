<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\PublicInvoiceController;
use App\Services\InvoiceExportService;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoices.show');
    Route::get('/invoices/{invoice}/pdf', [InvoicePdfController::class, 'download'])
        ->name('invoices.pdf');
    Route::middleware(['auth'])->get('/invoices/export/csv', function (InvoiceExportService $service) {
        return $service->exportCsv(request()->only(['status', 'from', 'to']));
        })->name('invoices.export.csv');

});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE (NO AUTH)
|--------------------------------------------------------------------------
*/
Route::get('/public/invoice/{token}', [PublicInvoiceController::class, 'show'])
        ->name('public.invoice.show');
