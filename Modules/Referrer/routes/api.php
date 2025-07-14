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
        Route::get('referrers/all', [ReferrerController::class, 'all']);
        Route::delete('referrers/force-delete/{id}', [ReferrerController::class, 'forceDelete']);
        Route::post('referrers/{id}/restore', [ReferrerController::class, 'restore']);
        Route::get('referrers/trash', [ReferrerController::class, 'trash']);
        Route::post('referrers/{id}/toggle-status', [ReferrerController::class, 'toggle_status']);
        Route::get('referrers/search', [ReferrerController::class, 'search']);
        Route::apiResource('referrers', ReferrerController::class)->names('referrers');
    });
