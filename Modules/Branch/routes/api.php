<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Branch\App\Http\Controllers\BranchController;

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
    Route::get('branch', fn (Request $request) => $request->user())->name('branch');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('branches/all', [BranchController::class, 'all']);
        Route::delete('branches/force-delete/{id}', [BranchController::class, 'forceDelete']);
        Route::post('branches/{id}/restore', [BranchController::class, 'restore']);
        Route::get('branches/trash', [BranchController::class, 'trash']);
        Route::post('branches/{id}/toggle-status', [BranchController::class, 'toggle_status']);
        Route::get('branches/search', [BranchController::class, 'search']);
        Route::apiResource('branches', BranchController::class)->names('branches');
    });
