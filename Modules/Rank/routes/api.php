<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Rank\App\Http\Controllers\RankController;

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
    Route::get('rank', fn (Request $request) => $request->user())->name('rank');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('ranks/all', [RankController::class, 'all']);
        Route::delete('ranks/force-delete/{id}', [RankController::class, 'forceDelete']);
        Route::post('ranks/{id}/restore', [RankController::class, 'restore']);
        Route::get('ranks/trash', [RankController::class, 'trash']);
        Route::post('ranks/{id}/toggle-status', [RankController::class, 'toggle_status']);
        Route::get('ranks/search', [RankController::class, 'search']);
        Route::apiResource('ranks', RankController::class)->names('ranks');
    });
