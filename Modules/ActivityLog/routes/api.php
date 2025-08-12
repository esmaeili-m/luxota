<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::get('activitylog', fn (Request $request) => $request->user())->name('activitylog');
});
Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('activity-log', [\Modules\ActivityLog\App\Http\Controllers\ActivityLogController::class, 'index']);
    });
