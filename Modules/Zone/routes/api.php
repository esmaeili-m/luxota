<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Zone\App\Http\Controllers\ZoneController;

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
    Route::get('zone', fn (Request $request) => $request->user())->name('zone');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {

        // ------------------------------
        // Zone extra routes
        // ------------------------------
        Route::get('zones/trash', [ZoneController::class, 'trash'])
            ->middleware('permission:zone.trash');

        Route::delete('zones/force-delete/{id}', [ZoneController::class, 'forceDelete'])
            ->middleware('permission:zone.delete');

        Route::post('zones/{id}/restore', [ZoneController::class, 'restore'])
            ->middleware('permission:zone.restore');


        // ------------------------------
        // Zone resource routes
        // ------------------------------
        Route::get('zones', [ZoneController::class, 'index'])
            ->name('zones.index')
            ->middleware('permission:zone.index');

        Route::post('zones', [ZoneController::class, 'store'])
            ->name('zones.store')
            ->middleware('permission:zone.create');

        Route::get('zones/{zone}', [ZoneController::class, 'show'])
            ->name('zones.show')
            ->middleware('permission:zone.index');

        Route::put('zones/{zone}', [ZoneController::class, 'update'])
            ->name('zones.update')
            ->middleware('permission:zone.update');

        Route::delete('zones/{zone}', [ZoneController::class, 'destroy'])
            ->name('zones.destroy')
            ->middleware('permission:zone.delete');
    });

