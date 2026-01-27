<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\PublicInvoiceController;
use App\Services\InvoiceExportService;

// App Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\InvoiceController as AppInvoiceController;
use App\Http\Controllers\App\ClientController;
use App\Http\Controllers\App\ProductController;

/*
|--------------------------------------------------------------------------
| LOGIN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE (NO AUTH)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| APP ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('app')
    ->name('app.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('invoices', AppInvoiceController::class);
        Route::resource('clients', ClientController::class);
        Route::resource('products', ProductController::class);
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
