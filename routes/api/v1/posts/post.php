<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('posts')
    ->name('posts.')
    ->group(function (): void {
        Route::get('', [PostController::class, 'index'])
            ->name('index');

        Route::get('pagination', [PostController::class, 'pagination'])
            ->name('pagination');

        Route::post('', [PostController::class, 'store'])
            ->name('store');

        Route::get('/{post}', [PostController::class, 'show'])
            ->name('show');

        Route::put('/{post}', [PostController::class, 'update'])
            ->name('update');

        Route::delete('/{post}', [PostController::class, 'destroy'])
            ->name('destroy');
    });
