<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Comment\CommentController;
use Illuminate\Support\Facades\Route;

Route::prefix('comments')
    ->name('comments.')
    ->group(function (): void {
        Route::get('', [CommentController::class, 'index'])
            ->name('index');

        Route::get('paginate', [CommentController::class, 'paginate'])
            ->name('paginate');

        Route::post('', [CommentController::class, 'store'])
            ->name('store');

        Route::get('/{id}', [CommentController::class, 'show'])
            ->name('show');

        Route::put('/{id}', [CommentController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [CommentController::class, 'destroy'])
            ->name('destroy');
    });
