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


        Route::get('invoices/get-vouchers-user', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'get_vouchers_user'])
            ->name('get.vouchers.user');

        Route::get('invoices/get-transactions-user/wallet', [\Modules\AccountingFinance\App\Http\Controllers\TransactionController::class, 'get_transactions_user_wallet'])
            ->name('get.transactions.user.wallet');

        Route::get('invoices/get-transactions-user/{id}', [\Modules\AccountingFinance\App\Http\Controllers\TransactionController::class, 'get_transactions_user'])
            ->name('get.transactions.user.voucher');

        Route::post('invoices/transaction-item', [\Modules\AccountingFinance\App\Http\Controllers\TransactionController::class, 'create_tranaction_item']);

        Route::post('invoices/remove-transaction-item-wallet/{invoiceId}', [\Modules\AccountingFinance\App\Http\Controllers\TransactionController::class, 'remove_transaction_item_wallet']);

        Route::post('invoices/remove-transaction-item/{voucherId}/{invoiceId}', [\Modules\AccountingFinance\App\Http\Controllers\TransactionController::class, 'remove_tranaction_item']);

        Route::get('invoices/claer_invoice/{invoiceId}', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'clear_invoice']);

        Route::post('invoices/redeem-voucher', [\Modules\AccountingFinance\App\Http\Controllers\VoucherController::class, 'redeem_voucher'])
            ->name('vouchers.redeem.voucherr');

        Route::get('invoices', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'index']);

        Route::post('invoices/invoice_add_item', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'invoice_add_item'])
            ->name('invoice.add.item');

        Route::middleware('auth:sanctum')->get('invoices/invoiceItem', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'get_invoice_items'])
            ->name('invoice.items');

        Route::delete('invoices/invoice_remove_item/{id}', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'invoice_remove_item'])
            ->name('invoice.remove.item');

        Route::middleware('auth:sanctum')->get('/invoice/get_invoice_item_count/', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'get_invoice_item_count'])
            ->name('invoice.get.invoice.item.count');

        Route::post('invoices', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'store'])
            ->name('invoice.create');

        Route::get('invoices/find-by-invoice-code/{invoice_code}', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'get_invoice'])
            ->name('invoice.find.by.invoice.code');

        Route::get('invoices/{invoiceCode}/transactions', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'invoice_transactions'])
            ->name('invoice.transactions');

        Route::get('invoices/get-invoices-user', [\Modules\AccountingFinance\App\Http\Controllers\InvoiceController::class, 'get_invoices_user'])
            ->name('invoice.list');
    });
