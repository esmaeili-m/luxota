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

        // ------------------------------
        // Product extra routes
        // ------------------------------
        Route::get('products/all', [ProductController::class, 'all'])
            ->middleware('permission:product.index');

        Route::get('products/trash', [ProductController::class, 'trash'])
            ->middleware('permission:product.trash');

        Route::get('products/search', [ProductController::class, 'search'])
            ->middleware('permission:product.index');

        Route::get('products/findBySlug/{slug}', [ProductController::class, 'find_by_slug']);

        Route::delete('products/force-delete/{id}', [ProductController::class, 'forceDelete'])
            ->middleware('permission:product.delete');

        Route::post('products/{id}/restore', [ProductController::class, 'restore'])
            ->middleware('permission:product.restore');

        Route::post('products/{id}/toggle-status', [ProductController::class, 'toggle_status'])
            ->middleware('permission:product.update');

        Route::get('products/{id}/with-category', [ProductController::class, 'showWithCategory'])
            ->middleware('permission:product.index');

        // ------------------------------
        // Product resource routes
        // ------------------------------
        Route::get('products', [ProductController::class, 'index'])
            ->name('products.index')
            ->middleware('permission:product.index');

        Route::post('products', [ProductController::class, 'store'])
            ->name('products.store')
            ->middleware('permission:product.create');

        Route::get('products/{product}', [ProductController::class, 'show'])
            ->name('products.show')
            ->middleware('permission:product.index');

        Route::put('products/{product}', [ProductController::class, 'update'])
            ->name('products.update')
            ->middleware('permission:product.update');

        Route::delete('products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy')
            ->middleware('permission:product.delete');
    });

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {

        // ------------------------------
        // Product extra routes
        // ------------------------------
        Route::get('cmments/all', [ProductController::class, 'all'])
            ->middleware('permission:product.index');

        Route::post('comments', [\Modules\Product\App\Http\Controllers\CommentController::class, 'store']);

    });

