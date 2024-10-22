<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('users')
    ->name('users.')
    ->group(function (): void {
        Route::get('', [UserController::class, 'index'])
            ->name('index');

        Route::get('pagination', [UserController::class, 'pagination'])
            ->name('pagination');

        Route::post('', [UserController::class, 'store'])
            ->name('store');

        Route::get('/{user}', [UserController::class, 'show'])
            ->name('show');

        Route::put('/{user}', [UserController::class, 'update'])
            ->name('update');

        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->name('destroy');
    });
