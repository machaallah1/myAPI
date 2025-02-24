<?php

declare(strict_types=1);

namespace App\Http\Requests\v1\PlaceDirection;

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
            'address_id' => [
                'description' => 'The ID of the associated address.',
                'type' => 'string',
                'example' => '01F7G8J6K9PQR',
            ],
            'place_id' => [
                'description' => 'The unique identifier for the place.',
                'type' => 'string',
                'example' => 'place-unique-id',
            ],
            'description' => [
                'description' => 'A brief description of the place.',
                'type' => 'string',
                'example' => 'A beautiful park located in the city center.',
            ],
            'main_text' => [
                'description' => 'Main descriptive text related to the place.',
                'type' => 'string',
                'example' => 'A great place for relaxation.',
            ],
            'secondary_text' => [
                'description' => 'Additional descriptive text related to the place.',
                'type' => 'string',
                'example' => 'Known for its beautiful walking paths and lakes.',
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
            'address_id' => 'nullable|string|exists:addresses,id',
            'place_id' => 'nullable|string|max:255|unique:place_directions,place_id',
            'description' => 'nullable|string|max:1000',
            'main_text' => 'nullable|string|max:1000',
            'secondary_text' => 'nullable|string|max:1000',
        ];
    }
}
