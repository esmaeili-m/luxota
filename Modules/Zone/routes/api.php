<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Zone\App\Http\Controllers\ZoneController;

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
    Route::get('zone', fn (Request $request) => $request->user())->name('zone');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('zones/all', [ZoneController::class, 'all']);
        Route::delete('zones/force-delete/{id}', [ZoneController::class, 'forceDelete']);
        Route::post('zones/{id}/restore', [ZoneController::class, 'restore']);
        Route::get('zones/trash', [ZoneController::class, 'trash']);
        Route::post('zones/{id}/toggle-status', [ZoneController::class, 'toggle_status']);
        Route::get('zones/search', [ZoneController::class, 'search']);
        Route::apiResource('zones', ZoneController::class)->names('zones');
    });
