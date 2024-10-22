<?php

declare(strict_types=1);

namespace App\Repositories\Concerns;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

interface AuthenticationContract
{
    /**
     * Authenticate provided user with a phone number
     */
    public function authenticate(FormRequest $request): User;

    /**
     * Generates an OTP code for a given user
     */
    public function generateOtp(string $email): OtpCode;

    /**
     * Log in the user with a given email and password
     */
    public function login(FormRequest $request): JsonResource|JsonResponse;

    /**
     * Log in the user with an OTP code
     */
    public function loginWithOtp(FormRequest $request): JsonResource|JsonResponse;

    /**
     * Generate and send an OTP code for a given user
     */
    public function sendOtp(string $email): bool;

    /**
     * Check if a the provided otp code is valid
     */
    public function verifyOtp(OtpCode $otpCode): JsonResponse|bool;
}
