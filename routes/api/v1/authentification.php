<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Authentification\AuthentificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:sanctum')->group(function (): void {
    
    Route::post('login', [AuthentificationController::class, 'login'])->name('login');

    Route::post('register', [AuthentificationController::class, 'register'])->name('register');

    Route::post('logout', [AuthentificationController::class, 'logout'])->name('logout');

});

