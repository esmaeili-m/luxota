<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\App\Http\Controllers\CategoryController;

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
    Route::get('category', fn (Request $request) => $request->user())->name('category');
});
Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {

        // ------------------------------
        // Category extra routes
        // ------------------------------
        Route::get('categories/all', [CategoryController::class, 'all'])
            ->middleware('permission:category.index');

        Route::get('categories/trash', [CategoryController::class, 'trash'])
            ->middleware('permission:category.trash');

        Route::get('categories/search', [CategoryController::class, 'search'])
            ->middleware('permission:category.index');

        Route::delete('categories/force-delete/{id}', [CategoryController::class, 'forceDelete'])
            ->middleware('permission:category.delete');

        Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])
            ->middleware('permission:category.restore');

        Route::post('categories/{id}/toggle-status', [CategoryController::class, 'toggle_status'])
            ->middleware('permission:category.update');

        Route::get('categories/{id}/with-children', [CategoryController::class, 'showWithChildren'])
            ->middleware('permission:category.index');

        Route::get('categories/{id}/with-parent', [CategoryController::class, 'showWithParent'])
            ->middleware('permission:category.index');

        Route::get('categories/sub-category/{id}', [CategoryController::class, 'category_children'])
            ->middleware('permission:category.index');

        Route::get('categories/findBySlug/{slug}', [CategoryController::class, 'find_by_slug']);


        // ------------------------------
        // Category resource routes
        // ------------------------------
        Route::get('categories', [CategoryController::class, 'index'])
            ->name('categories.index');


        Route::post('categories', [CategoryController::class, 'store'])
            ->name('categories.store')
            ->middleware('permission:category.create');

        Route::get('categories/{category}', [CategoryController::class, 'show'])
            ->name('categories.show')
            ->middleware('permission:category.index');

        Route::put('categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update')
            ->middleware('permission:category.update');

        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy')
            ->middleware('permission:category.delete');
    });

