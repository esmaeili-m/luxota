<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Product\App\Http\Controllers\ProductController;

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
    Route::get('product', fn (Request $request) => $request->user())->name('product');
});
Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('products/all', [ProductController::class, 'all'])->name('product.all');
        Route::get('products/search', [ProductController::class, 'search']);
        Route::get('products/{id}/with-category', [ProductController::class, 'showWithChildren'])->name('product.showWithCategory');
        Route::apiResource('products', ProductController::class)->names('product');

    });
