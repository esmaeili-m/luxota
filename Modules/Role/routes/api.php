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

Route::prefix('v1/roles')
    ->name('api.v1.')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->middleware('permission:role.index');

        Route::post('/', [RoleController::class, 'store'])
            ->middleware('permission:role.create');

        Route::get('/trash', [RoleController::class, 'trash'])
            ->middleware('permission:role.trash');

        Route::get('/{role}', [RoleController::class, 'show'])
            ->middleware('permission:role.index');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->middleware('permission:role.update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:role.delete');

        Route::delete('/force-delete/{id}', [RoleController::class, 'forceDelete'])
            ->middleware('permission:role.delete');

        Route::post('/{id}/restore', [RoleController::class, 'restore'])
            ->middleware('permission:role.restore');

        Route::post('/{id}/assign-permissions', [RoleController::class, 'assignPermissions'])
            ->middleware('permission:role.restore');


    });
