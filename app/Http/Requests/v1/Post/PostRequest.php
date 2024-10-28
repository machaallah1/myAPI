<?php

namespace App\Http\Requests\v1\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'status' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
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
            'title' => [
                'description' => 'The title of the post.',
                'type' => 'string',
                'example' => 'Post Title',
            ],
            'content' => [
                'description' => 'The content of the post.',
                'type' => 'string',
                'example' => 'Post Content',
            ],
            'image' => [
                'description' => 'The image of the post.',
                'type' => 'string',
                'example' => 'Post Image',
            ],
            'status' => [
                'description' => 'The status of the post.',
                'type' => 'string',
                'example' => 'Post Status',
            ],
            'slug' => [
                'description' => 'The slug of the post.',
                'type' => 'string',
                'example' => 'post-slug',
            ],
        ];
    }
}
