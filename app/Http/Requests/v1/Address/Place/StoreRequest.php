<?php

declare(strict_types=1);

namespace App\Http\Requests\v1\Address\Place;

use Illuminate\Foundation\Http\FormRequest;

final class StoreRequest extends FormRequest
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
            'latitude' => [
                'description' => 'The latitude of the place.',
                'type' => 'decimal',
                'example' => '40.712776',
            ],
            'longitude' => [
                'description' => 'The longitude of the place.',
                'type' => 'decimal',
                'example' => '-74.005974',
            ],
            'user_id' => [
                'description' => 'The user ID associated with this place.',
                'type' => 'string',
                'example' => '01F7G8J6K9PQR',
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
            'latitude' => ['required', 'decimal:0,10'],
            'longitude' => ['required', 'decimal:0,10'],
            'user_id' => 'nullable|string|exists:users,id',

        ];
    }
}
