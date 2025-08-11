<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Rank\App\Http\Controllers\RankController;

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
    Route::get('rank', fn (Request $request) => $request->user())->name('rank');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {

        Route::get('ranks/all', [RankController::class, 'all'])
            ->middleware('permission:rank.index');

        Route::delete('ranks/force-delete/{id}', [RankController::class, 'forceDelete'])
            ->middleware('permission:rank.delete');

        Route::post('ranks/{id}/restore', [RankController::class, 'restore'])
            ->middleware('permission:rank.restore');

        Route::get('ranks/trash', [RankController::class, 'trash'])
            ->middleware('permission:rank.trash');

        Route::post('ranks/{id}/toggle-status', [RankController::class, 'toggle_status'])
            ->middleware('permission:rank.update');

        Route::get('ranks/search', [RankController::class, 'search'])
            ->middleware('permission:rank.index');

        Route::get('ranks', [RankController::class, 'index'])
            ->name('ranks.index')
            ->middleware('permission:rank.index');

        Route::post('ranks', [RankController::class, 'store'])
            ->name('ranks.store')
            ->middleware('permission:rank.create');

        Route::get('ranks/{rank}', [RankController::class, 'show'])
            ->name('ranks.show')
            ->middleware('permission:rank.index');

        Route::put('ranks/{rank}', [RankController::class, 'update'])
            ->name('ranks.update')
            ->middleware('permission:rank.update');

        Route::patch('ranks/{rank}', [RankController::class, 'update'])
            ->name('ranks.update')
            ->middleware('permission:rank.update');

        Route::delete('ranks/{rank}', [RankController::class, 'destroy'])
            ->name('ranks.destroy')
            ->middleware('permission:rank.delete');
    });

