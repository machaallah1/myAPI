<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Comment\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('comments')
    ->name('comments.')
    ->group(function (): void {
        Route::get('', [CommentController::class, 'index'])
            ->name('index');

        Route::get('pagination', [CommentController::class, 'pagination'])
            ->name('pagination');

        Route::post('', [CommentController::class, 'store'])
            ->name('store');

        Route::get('/{comment}', [CommentController::class, 'show'])
            ->name('show');

        Route::put('/{comment}', [CommentController::class, 'update'])
            ->name('update');

        Route::delete('/{comment}', [CommentController::class, 'destroy'])
            ->name('destroy');
    });
