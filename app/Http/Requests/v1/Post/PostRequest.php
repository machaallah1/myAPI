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
            'status' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9-]+$/',
            'user_id' => 'required|integer|exists:users,id',
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'tag_id' => [
                'nullable',
                'array',
            ],
            'tag_id.*' => [
                'string',
                'exists:tags,id',
            ],
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
                'type' => 'file',
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
            'user_id' => [
                'description' => 'The ID of the user.',
                'type' => 'integer',
                'example' => 1,
            ],
            'category_id' => [
                'description' => 'The ID of the category.',
                'type' => 'integer',
                'example' => 1,
            ],
            'tag_id' => [
                'description' => 'List of tag IDs associated with the product.',
                'type' => 'string',
                'example' => '1, 2, 3',
            ],
        ];
    }
}
