<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Language\App\Http\Controllers\LanguageController;
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
    Route::get('language', fn (Request $request) => $request->user())->name('language');
});
Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::apiResource('languages', LanguageController::class)->names('languages');
    });
