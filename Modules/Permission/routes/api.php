<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Modules\Permission\App\Http\Controllers\PermissionController;

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


Route::prefix('v1/permissions')
    ->name('api.v1.')
    ->group(function () {


        // ------------------------------
        // Category resource routes
        // ------------------------------
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/getGroupedPermissions', [PermissionController::class, 'getGroupedPermissions']);

    });
