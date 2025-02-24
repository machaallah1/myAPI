<?php

declare(strict_types=1);

namespace App\Http\Requests\v1\Address;

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
            'place_id' => [
                'description' => 'The ID of the place.',
                'type' => 'string',
                'example' => 'ChIJN1t_tDeuEmsRUsoyG83frY4',
            ],
            'place_name' => [
                'description' => 'The name of the place.',
                'type' => 'string',
                'example' => 'Google Sydney',
            ],
            'longitude' => [
                'description' => 'The longitude of the address.',
                'type' => 'float',
                'example' => '151.1957362',
            ],
            'latitude' => [
                'description' => 'The latitude of the address.',
                'type' => 'float',
                'example' => '-33.8670522',
            ],
            'formated_address' => [
                'description' => 'The formatted address of the address.',
                'type' => 'string',
                'example' => 'Street Address',
            ],
            'user_id' => [
                'description' => 'The ID of the user associated with the address.',
                'type' => 'string',
                'example' => '01F7G8J6K9PQR',
            ],
            'street_name' => [
                'description' => 'The street name of the address.',
                'type' => 'string',
                'example' => 'Boulevard Saint-Germain',
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
            'place_id' => 'nullable|string|max:255',
            'place_name' => 'nullable|string|max:255',
            'longitude' => ['nullable', 'decimal:0,10'],
            'latitude' => ['nullable', 'decimal:0,10'],
            'street_name' => 'nullable|string|max:255',
            'user_id' => 'nullable|string|exists:users,id',
            'formated_address' => 'nullable|string|max:255',
        ];
    }
}
