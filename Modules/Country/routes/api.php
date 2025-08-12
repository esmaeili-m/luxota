<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Country\App\Http\Controllers\CountryController;

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
    Route::get('country', fn (Request $request) => $request->user())->name('country');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('countries/all', [CountryController::class, 'all']);
        Route::apiResource('countries', CountryController::class)->names('countries');
        Route::post('countries/{id}/toggle-status', [CountryController::class, 'toggle_status'])->name('countries.toggle-status');

        // Trash routes
        Route::get('countries/trash', [CountryController::class, 'trash'])->name('countries.trash');
        Route::patch('countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
        Route::delete('countries/{id}/force-delete', [CountryController::class, 'forceDelete'])->name('countries.force-delete');
    });
