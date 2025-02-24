<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Tag\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('tags')
    ->name('tags.')
    ->group(function (): void {
        Route::get('', [TagController::class, 'index'])
            ->name('index');

        Route::get('paginate', [TagController::class, 'paginate'])
            ->name('paginate');

        Route::post('', [TagController::class, 'store'])
            ->name('store');

        Route::get('{id}', [TagController::class, 'show'])
            ->name('show')->middleware('auth:sanctum');

        Route::put('{id}', [TagController::class, 'update'])
            ->name('update')->middleware('auth:sanctum');

        Route::delete('{id}', [TagController::class, 'destroy'])
            ->name('destroy')->middleware('auth:sanctum');
    });
