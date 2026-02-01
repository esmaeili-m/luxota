<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::get('support', fn (Request $request) => $request->user())->name('support');
});
Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('support/user-tickets', [\Modules\Support\App\Http\Controllers\TicketController::class, 'get_user_tickets'])
            ->middleware('permission:role.index');

        Route::get('support/tickets/{id}', [\Modules\Support\App\Http\Controllers\TicketController::class, 'get_ticket']);

        Route::Post('support/tickets', [\Modules\Support\App\Http\Controllers\TicketController::class, 'tickets']);
        Route::get('support/tickets', [\Modules\Support\App\Http\Controllers\TicketController::class, 'get_tickets']);
        Route::put('support/tickets/{id}', [\Modules\Support\App\Http\Controllers\TicketController::class, 'update']);
        Route::get('support/ticket-count', [\Modules\Support\App\Http\Controllers\TicketController::class, 'ticket_count']);
        Route::delete('support/{id}', [\Modules\Support\App\Http\Controllers\TicketController::class, 'destroy']);
//        Route::post('roles', [\Modules\Support\App\Http\Controllers\TicketController::class, 'store'])
//            ->name('roles.store')
//            ->middleware('permission:role.create');
//
//        Route::get('roles/trash', [\Modules\Support\App\Http\Controllers\TicketController::class, 'trash'])
//            ->middleware('permission:role.trash');
//
//        Route::get('roles/{role}', [\Modules\Support\App\Http\Controllers\TicketController::class, 'show'])
//            ->name('roles.show')
//            ->middleware('permission:role.index');
//
//        Route::patch('roles/{role}', [\Modules\Support\App\Http\Controllers\TicketController::class, 'update'])
//            ->middleware('permission:role.update');
//
//        Route::delete('roles/{role}', [\Modules\Support\App\Http\Controllers\TicketController::class, 'destroy'])
//            ->name('roles.destroy')
//            ->middleware('permission:role.delete');
//
//        Route::delete('roles/force-delete/{id}', [\Modules\Support\App\Http\Controllers\TicketController::class, 'forceDelete'])
//            ->middleware('permission:role.delete');
//
//        Route::post('roles/{id}/restore', [\Modules\Support\App\Http\Controllers\TicketController::class, 'restore'])
//            ->middleware('permission:role.restore');


    });

