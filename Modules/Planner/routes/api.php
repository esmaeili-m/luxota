<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Planner\App\Http\Controllers\TagController;
use Modules\Planner\App\Http\Controllers\SprintController;
use Modules\Planner\App\Http\Controllers\BoardController;
use Modules\Planner\App\Http\Controllers\ColumnController;
use Modules\Planner\App\Http\Controllers\TaskController;
use Modules\Planner\App\Http\Controllers\TeamController;
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
    Route::get('planner', fn (Request $request) => $request->user())->name('planner');
});
Route::prefix('v1/tags')
    ->name('api.v1.')
    ->group(function () {
        Route::get('/', [TagController::class, 'index'])
            ->middleware('permission:zone.index');

        Route::Post('/', [TagController::class, 'store'])
            ->middleware('permission:zone.index');

        Route::Put('/{id}', [TagController::class, 'update'])
            ->middleware('permission:zone.index');
    });

Route::prefix('v1/sprints')
    ->name('api.v1.')
    ->group(function () {
        Route::get('/', [SprintController::class, 'index'])
            ->middleware('permission:zone.index');

        Route::Post('/', [SprintController::class, 'store'])
            ->middleware('permission:zone.index');

        Route::Put('/{id}', [SprintController::class, 'update'])
            ->middleware('permission:zone.index');
    });
Route::prefix('v1/boards')
    ->name('api.v1.')
    ->group(function () {
        Route::get('/', [BoardController::class, 'index'])
            ->middleware('permission:zone.index');
        Route::get('/{id}', [BoardController::class, 'show'])
            ->middleware('permission:zone.index');

        Route::Post('/', [BoardController::class, 'store'])
            ->middleware('permission:zone.index');

        Route::Put('/{id}', [BoardController::class, 'update'])
            ->middleware('permission:zone.index');
    });
Route::prefix('v1/columns')
    ->name('api.v1.')
    ->group(function () {
        Route::get('/', [ColumnController::class, 'index'])
            ->middleware('permission:zone.index');
        Route::get('/{id}', [ColumnController::class, 'show'])
            ->middleware('permission:zone.index');

        Route::Post('/', [ColumnController::class, 'store'])
            ->middleware('permission:zone.index');

        Route::Put('/{id}', [ColumnController::class, 'update'])
            ->middleware('permission:zone.index');
    });
Route::prefix('v1/tasks')
    ->name('api.v1.')
    ->group(function () {
        Route::get('/', [TaskController::class, 'index'])
            ->middleware('permission:zone.index');
        Route::get('/{id}', [TaskController::class, 'show'])
            ->middleware('permission:zone.index');

        Route::Post('/', [TaskController::class, 'store'])
            ->middleware('permission:zone.index');

        Route::Put('/{id}', [TaskController::class, 'update'])
            ->middleware('permission:zone.index');
    });

Route::prefix('v1/teams')
    ->name('api.v1.')
    ->group(function () {
        Route::get('/', [TeamController::class, 'index'])
            ->middleware('permission:zone.index');
        Route::get('/{id}', [TeamController::class, 'show'])
            ->middleware('permission:zone.index');

        Route::Post('/', [TeamController::class, 'store'])
            ->middleware('permission:zone.index');

        Route::Put('/{id}', [TeamController::class, 'update'])
            ->middleware('permission:zone.index');
    });
