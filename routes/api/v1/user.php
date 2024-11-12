<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')
    ->name('users.')
    ->group(function (): void {
        Route::get('', [UserController::class, 'index'])
            ->name('index');

        Route::get('paginate', [UserController::class, 'paginate'])
            ->name('paginate');

        Route::post('', [UserController::class, 'store'])
            ->name('store');

        Route::get('/{id}', [UserController::class, 'show'])
            ->name('show');

        Route::put('/{id}', [UserController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [UserController::class, 'destroy'])
            ->name('destroy');
    });
