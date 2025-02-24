<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')
    ->name('categories.')
    ->group(function (): void {
        Route::get('', [CategoryController::class, 'index'])
            ->name('index');
        Route::get('paginate', [CategoryController::class, 'paginate'])
            ->name('paginate');
        Route::get('{id}', [CategoryController::class, 'show'])
            ->name('show');
        Route::post('', [CategoryController::class, 'store'])
            ->name('store')->middleware('auth:sanctum');
        Route::put('{id}', [CategoryController::class, 'update'])
            ->name('update')->middleware('auth:sanctum');
        Route::delete('{id}', [CategoryController::class, 'destroy'])
            ->name('destroy')->middleware('auth:sanctum');
    });
