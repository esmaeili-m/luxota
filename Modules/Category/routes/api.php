<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\App\Http\Controllers\CategoryController;

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
    Route::get('category', fn (Request $request) => $request->user())->name('category');
});
Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('categories/all', [CategoryController::class, 'all'])->name('categories.all');
        Route::post('categories/{id}/toggle-status', [CategoryController::class, 'toggle_status'])->name('categories.toggle_status');
        Route::get('categories/search', [CategoryController::class, 'search']);
        Route::get('categories/{id}/with-children', [CategoryController::class, 'showWithChildren'])->name('categories.showWithChildren');
        Route::get('categories/{id}/with-parent', [CategoryController::class, 'showWithParent'])->name('categories.showWithParent');
        Route::apiResource('categories', CategoryController::class)->names('categories');

    });
