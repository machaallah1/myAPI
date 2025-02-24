<?php

declare(strict_types=1);

use App\Http\Controllers\api\v1\Authentication\AuthenticationController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest:sanctum')->group(function (): void {
    Route::post('authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');
    Route::post('login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('otp-login', [AuthenticationController::class, 'loginWithOtp'])->name('login.with.otp');
    Route::post('resend-otp/{user}', [AuthenticationController::class, 'resendOtp'])->name('resend.otp');
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
});
