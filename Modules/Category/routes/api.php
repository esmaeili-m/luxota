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


Route::prefix('v1/categories')
    ->name('api.v1.')
    ->group(function () {
        // ------------------------------
        // Category extra routes
        // ------------------------------

        Route::get('/trash', [CategoryController::class, 'trash'])
            ->middleware('permission:category.trash');

        Route::delete('/force-delete/{id}', [CategoryController::class, 'forceDelete'])
            ->middleware('permission:category.delete');

        Route::post('/{id}/restore', [CategoryController::class, 'restore'])
            ->middleware('permission:category.restore');

        Route::get('/findBySlug/{slug}', [CategoryController::class, 'find_by_slug'])
            ->middleware('permission:category.index');


        // ------------------------------
        // Category resource routes
        // ------------------------------
        Route::get('/', [CategoryController::class, 'index'])
            ->middleware('permission:category.index');

        Route::post('/', [CategoryController::class, 'store'])
            ->middleware('permission:category.create');

        Route::get('/{category}', [CategoryController::class, 'show'])
            ->middleware('permission:category.index');

        Route::put('/{category}', [CategoryController::class, 'update'])
            ->middleware('permission:category.update');

        Route::delete('/{category}', [CategoryController::class, 'destroy'])
            ->middleware('permission:category.delete');
    });

