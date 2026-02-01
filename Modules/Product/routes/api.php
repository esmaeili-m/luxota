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

Route::prefix('v1/products')
    ->name('api.v1.')
    ->group(function () {

        // ------------------------------
        // Product extra routes
        // ------------------------------

        Route::get('/trash', [ProductController::class, 'trash'])
            ->middleware('permission:product.trash');

        Route::get('/findBySlug/{slug}', [ProductController::class, 'find_by_slug'])
            ->middleware('permission:product.index');

        Route::delete('/force-delete/{id}', [ProductController::class, 'forceDelete'])
            ->middleware('permission:product.delete');

        Route::post('/{id}/restore', [ProductController::class, 'restore'])
            ->middleware('permission:product.restore');

        // ------------------------------
        // Product resource routes
        // ------------------------------
        Route::get('/', [ProductController::class, 'index'])
            ->middleware('permission:product.index');

        Route::post('/', [ProductController::class, 'store'])
            ->middleware('permission:product.create');

        Route::get('/{product}', [ProductController::class, 'show'])
            ->middleware('permission:product.index');

        Route::put('/{product}', [ProductController::class, 'update'])
            ->middleware('permission:product.update');

        Route::delete('/{product}', [ProductController::class, 'destroy'])
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

