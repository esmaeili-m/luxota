<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Referrer\App\Http\Controllers\ReferrerController;

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
    Route::get('referrer', fn (Request $request) => $request->user())->name('referrer');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {

        // ------------------------------
        // Referrer extra routes
        // ------------------------------
        Route::get('referrers/all', [ReferrerController::class, 'all'])
            ->middleware('permission:referrer.index');

        Route::get('referrers/trash', [ReferrerController::class, 'trash'])
            ->middleware('permission:referrer.trash');

        Route::get('referrers/search', [ReferrerController::class, 'search'])
            ->middleware('permission:referrer.index');

        Route::delete('referrers/force-delete/{id}', [ReferrerController::class, 'forceDelete'])
            ->middleware('permission:referrer.delete');

        Route::post('referrers/{id}/restore', [ReferrerController::class, 'restore'])
            ->middleware('permission:referrer.restore');

        Route::post('referrers/{id}/toggle-status', [ReferrerController::class, 'toggle_status'])
            ->middleware('permission:referrer.update');

        // ------------------------------
        // Referrer resource routes
        // ------------------------------
        Route::get('referrers', [ReferrerController::class, 'index'])
            ->name('referrers.index')
            ->middleware('permission:referrer.index');

        Route::post('referrers', [ReferrerController::class, 'store'])
            ->name('referrers.store')
            ->middleware('permission:referrer.create');

        Route::get('referrers/{referrer}', [ReferrerController::class, 'show'])
            ->name('referrers.show')
            ->middleware('permission:referrer.index');

        Route::put('referrers/{referrer}', [ReferrerController::class, 'update'])
            ->name('referrers.update')
            ->middleware('permission:referrer.update');

        Route::delete('referrers/{referrer}', [ReferrerController::class, 'destroy'])
            ->name('referrers.destroy')
            ->middleware('permission:referrer.delete');
    });

