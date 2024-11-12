<?php

namespace App\Http\Requests\v1\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'post_id' => 'required|exists:posts,id',
            'content' => '  required|string|max:255',
            'user_id' => 'required|exists:users,id',

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
            'post_id' => [
                'description' => 'The ID of the post that the comment belongs to.',
                'example' => '1',
            ],
            'content' => [
                'description' => 'The content of the comment.',
                'example' => 'This is a comment.',
            ],
            'user_id' => [
                'description' => 'The ID of the user who created the comment.',
                'example' => '1',
            ],
        ];
    }
}
