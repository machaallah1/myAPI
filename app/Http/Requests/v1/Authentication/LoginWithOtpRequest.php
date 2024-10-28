<?php

declare(strict_types=1);

namespace App\Http\Requests\v1\Authentication;

use Illuminate\Foundation\Http\FormRequest;

final class LoginWithOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retrieves the body parameters for the function.
     *
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'The email address of the user.',
                'example' => 'user@example.com',
            ],
            'otp' => [
                'description' => 'The OTP code.',
                'type' => 'string',
                'example' => '123456',
            ],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|numeric',
        ];
    }
}
