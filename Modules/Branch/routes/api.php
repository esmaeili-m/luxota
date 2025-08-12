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

        // ------------------------------
        // Branch extra routes
        // ------------------------------
        Route::get('branches/all', [BranchController::class, 'all'])
            ->middleware('permission:branch.index');

        Route::get('branches/trash', [BranchController::class, 'trash'])
            ->middleware('permission:branch.trash');

        Route::get('branches/search', [BranchController::class, 'search'])
            ->middleware('permission:branch.index');

        Route::delete('branches/force-delete/{id}', [BranchController::class, 'forceDelete'])
            ->middleware('permission:branch.delete');

        Route::post('branches/{id}/restore', [BranchController::class, 'restore'])
            ->middleware('permission:branch.restore');

        Route::post('branches/{id}/toggle-status', [BranchController::class, 'toggle_status'])
            ->middleware('permission:branch.update');

        // ------------------------------
        // Branch resource routes
        // ------------------------------
        Route::get('branches', [BranchController::class, 'index'])
            ->name('branches.index')
            ->middleware('permission:branch.index');

        Route::post('branches', [BranchController::class, 'store'])
            ->name('branches.store')
            ->middleware('permission:branch.create');

        Route::get('branches/{branch}', [BranchController::class, 'show'])
            ->name('branches.show')
            ->middleware('permission:branch.index');

        Route::put('branches/{branch}', [BranchController::class, 'update'])
            ->name('branches.update')
            ->middleware('permission:branch.update');

        Route::delete('branches/{branch}', [BranchController::class, 'destroy'])
            ->name('branches.destroy')
            ->middleware('permission:branch.delete');
    });

