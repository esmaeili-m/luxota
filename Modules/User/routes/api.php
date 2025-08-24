<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\UserController;

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
    Route::get('user', fn (Request $request) => $request->user())->name('user');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::get('users/all', [UserController::class, 'all'])
            ->name('users.all')
            ->middleware('permission:user.index');

        Route::get('users/find-by-role/{role}', [UserController::class, 'findByRole'])
            ->name('users.find_by_role')
            ->middleware('permission:user.index');

        Route::get('users/user-form-data', [UserController::class, 'UserFormData'])
            ->name('users.form_data')
            ->middleware('permission:user.create');

        Route::post('users/user-role/{role}', [UserController::class, 'indexByRoleName'])
            ->name('users.index_by_role');

        Route::delete('users/force-delete/{id}', [UserController::class, 'forceDelete'])
            ->name('users.force_delete')
            ->middleware('permission:user.delete');

        Route::post('users/{id}/restore', [UserController::class, 'restore'])
            ->name('users.restore')
            ->middleware('permission:user.restore');

        Route::get('users/trash', [UserController::class, 'trash'])
            ->name('users.trash')
            ->middleware('permission:user.trash');

        Route::post('users/{id}/toggle-status', [UserController::class, 'toggle_status'])
            ->name('users.toggle_status')
            ->middleware('permission:user.update');

        Route::get('users/search', [UserController::class, 'search'])
            ->name('users.search');

        Route::get('users', [UserController::class, 'index'])
            ->name('users.index')
            ->middleware('permission:user.index');

        Route::get('users/{user}', [UserController::class, 'show'])
            ->name('users.show')
            ->middleware('permission:user.index');

        Route::post('users', [UserController::class, 'store'])
            ->name('users.store')
            ->middleware('permission:user.create');

        Route::put('users/{user}', [UserController::class, 'update'])
            ->name('users.update')
            ->middleware('permission:user.update');

        Route::delete('users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy')
            ->middleware('permission:user.delete');
    });
