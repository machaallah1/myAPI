<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:8|max:12',
            'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }

    /**
     * Retrieves the body parameters for the function.
     *
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The name of the user.',
                'type' => 'string',
                'example' => 'John Doe',
            ],
            'email' => [
                'description' => 'The email of the user.',
                'type' => 'string',
                'example' => 'n9vI3@example.com',
            ],
            'password' => [
                'description' => 'The password of the user.',
                'type' => 'string',
                'example' => 'password',
            ],
            'role' => [
                'description' => 'The role of the user.',
                'type' => 'string',
                'example' => 'admin',
            ],
            'phone' => [
                'description' => 'The phone number of the user.',
                'type' => 'string',
                'example' => '123456789',
            ],
            'image' => [
                'description' => 'The image of the user.',
                'type' => 'file',
                'example' => 'image.png',
            ],
        ];
    }
}
