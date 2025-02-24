<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Like\LikeController;
use Illuminate\Support\Facades\Route;

Route::prefix('likes')
    ->name('likes.')
    ->group(function (): void {
        Route::get('', [LikeController::class, 'index'])
            ->name('index');

        Route::get('paginate', [LikeController::class, 'paginate'])
            ->name('paginate');

        Route::delete('/{id}', [LikeController::class, 'destroy'])
            ->name('destroy')->middleware('auth:sanctum');

        Route::post('', [LikeController::class, 'store'])
            ->name('store')->middleware('auth:sanctum');
    });
