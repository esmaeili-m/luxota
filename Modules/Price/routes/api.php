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
    Route::get('price', fn (Request $request) => $request->user())->name('price');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {

        // ------------------------------
        // Prices extra routes
        // ------------------------------
        Route::get('prices/{id}', [\Modules\Price\App\Http\Controllers\PriceController::class, 'index'])
            ->middleware('permission:product.price');

        Route::post('prices', [\Modules\Price\App\Http\Controllers\PriceController::class, 'store'])
            ->middleware('permission:product.price');

        Route::delete('prices/{id}', [\Modules\Price\App\Http\Controllers\PriceController::class, 'destroy'])
            ->middleware('permission:product.price');
    });


