<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Role\App\Http\Controllers\RoleController;

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
        Route::get('roles/all', [RoleController::class, 'all']);
        Route::delete('roles/force-delete/{id}', [RoleController::class, 'forceDelete']);
        Route::post('roles/{id}/restore', [RoleController::class, 'restore']);
        Route::get('roles/trash', [RoleController::class, 'trash']);
        Route::post('roles/{id}/toggle-status', [RoleController::class, 'toggle_status']);
        Route::get('roles/search', [RoleController::class, 'search']);
        Route::get('roles/{id}/with-children', [RoleController::class, 'showWithChildren']);
        Route::get('roles/{id}/with-parent', [RoleController::class, 'showWithParent']);
        Route::apiResource('roles', RoleController::class)->names('roles');

    });
