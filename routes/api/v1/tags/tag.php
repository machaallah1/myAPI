<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Tag\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('tags')
    ->name('tags.')
    ->group(function (): void {
        Route::get('', [TagController::class, 'index'])
            ->name('index');

        Route::get('pagination', [TagController::class, 'pagination'])
            ->name('pagination');

        Route::post('', [TagController::class, 'store'])
            ->name('store');

        Route::get('/{tag}', [TagController::class, 'show'])
            ->name('show');

        Route::put('/{tag}', [TagController::class, 'update'])
            ->name('update');

        Route::delete('/{tag}', [TagController::class, 'destroy'])
            ->name('destroy');
    });
