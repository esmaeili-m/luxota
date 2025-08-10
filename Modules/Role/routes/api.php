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
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('roles/all', [RoleController::class, 'all'])
            ->middleware('permission:role.index');

        Route::delete('roles/force-delete/{id}', [RoleController::class, 'forceDelete'])
            ->middleware('permission:role.delete');

        Route::post('roles/{id}/restore', [RoleController::class, 'restore'])
            ->middleware('permission:role.restore');

        Route::get('roles/trash', [RoleController::class, 'trash'])
            ->middleware('permission:role.trash');

        Route::post('roles/{id}/toggle-status', [RoleController::class, 'toggle_status'])
            ->middleware('permission:role.update');

        Route::get('roles/search', [RoleController::class, 'search'])
            ->middleware('permission:role.index');

        // به جای apiResource
        Route::get('roles', [RoleController::class, 'index'])
            ->name('roles.index')
            ->middleware('permission:role.index');

        Route::post('roles', [RoleController::class, 'store'])
            ->name('roles.store')
            ->middleware('permission:role.create');

        Route::get('roles/{role}', [RoleController::class, 'show'])
            ->name('roles.show')
            ->middleware('permission:role.index');

        Route::put('roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update')
            ->middleware('permission:role.update');

        Route::patch('roles/{role}', [RoleController::class, 'update'])
            ->middleware('permission:role.update');

        Route::delete('roles/{role}', [RoleController::class, 'destroy'])
            ->name('roles.destroy')
            ->middleware('permission:role.delete');
    });
