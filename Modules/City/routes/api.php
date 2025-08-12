<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\City\App\Http\Controllers\CityController;

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
    Route::get('city', fn (Request $request) => $request->user())->name('city');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('cities/all', [CityController::class, 'all']);
        Route::delete('cities/force-delete/{id}', [CityController::class, 'forceDelete']);
        Route::post('cities/{id}/restore', [CityController::class, 'restore']);
        Route::get('cities/trash', [CityController::class, 'trash']);
        Route::post('cities/{id}/toggle-status', [CityController::class, 'toggle_status']);
        Route::get('cities/search', [CityController::class, 'search']);
        Route::apiResource('cities', CityController::class)->names('cities');
    });
