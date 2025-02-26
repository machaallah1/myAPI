<?php

declare(strict_types=1);

use App\Http\Controllers\Api\v1\Address\AddressController;
use App\Http\Controllers\Api\v1\Address\UserAddressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->prefix('addresses')
    ->name('addresses.')
    ->group(function (): void {
        Route::get('', [AddressController::class, 'index'])
            ->name('index');
        Route::get('paginate', [AddressController::class, 'paginate'])
            ->name('paginate');
        Route::post('', [AddressController::class, 'store'])
            ->name('store');
        Route::get('{id}', [AddressController::class, 'show'])
            ->name('show');
        Route::put('{id}', [AddressController::class, 'update'])
            ->name('update');
        Route::delete('{id}', [AddressController::class, 'destroy'])
            ->name('destroy');

        Route::get('{user}', UserAddressController::class)
            ->name('user');
    });
