<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('accountingfinance', fn (Request $request) => $request->user())->name('accountingfinance');
});
Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::get('vouchers/all', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'all'])
            ->name('vouchers.all')
            ->middleware('permission:user.index');

        Route::get('vouchers/trash', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'trash'])
            ->name('vouchers.trash')
            ->middleware('permission:user.trash');

        Route::post('vouchers/{id}/toggle-status', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'toggle_status'])
            ->name('vouchers.toggle_status')
            ->middleware('permission:user.update');

        Route::get('vouchers/search', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'search'])
            ->name('vouchers.search');

        Route::get('vouchers', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'index'])
            ->name('vouchers.index')
            ->middleware('permission:user.index');

        Route::get('vouchers/{id}', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'show'])
            ->name('vouchers.show')
            ->middleware('permission:user.index');

        Route::post('vouchers', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'store'])
            ->name('vouchers.store')
            ->middleware('permission:user.create');

        Route::put('vouchers/{voucher}', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'update'])
            ->name('vouchers.update')
            ->middleware('permission:user.update');

        Route::delete('vouchers/{user}', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'destroy'])
            ->name('vouchers.destroy')
            ->middleware('permission:user.delete');

        Route::post('invoices/invoice_add_item', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'invoice_add_item'])
            ->name('invoice.add.item');

        Route::delete('invoices/invoice_remove_item/{id}', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'invoice_remove_item'])
            ->name('invoice.remove.item');
    });
