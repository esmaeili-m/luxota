<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\UserController;

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
    Route::get('user', fn (Request $request) => $request->user())->name('user');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('users/all', [UserController::class, 'all']);
        Route::delete('users/force-delete/{id}', [UserController::class, 'forceDelete']);
        Route::post('users/{id}/restore', [UserController::class, 'restore']);
        Route::get('users/trash', [UserController::class, 'trash']);
        Route::post('users/{id}/toggle-status', [UserController::class, 'toggle_status']);
        Route::get('users/search', [UserController::class, 'search']);
        Route::apiResource('users', UserController::class)->names('users');
    });
