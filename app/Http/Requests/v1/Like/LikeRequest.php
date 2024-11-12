<?php

namespace App\Http\Requests\v1\Like;

use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'liked' => 'required|boolean',
            'comment_id' => 'nullable|exists:comments,id', // Rendre comment_id optionnel
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
            'user_id' => [
                'description' => 'The ID of the user who liked the post.',
                'example' => '1',
            ],
            'post_id' => [
                'description' => 'The ID of the post that was liked.',
                'example' => '1',
            ],
            'liked' => [
                'description' => 'Whether the user liked the post or not.',
                'example' => 'true',
            ],
            'comment_id' => [
                'description' => 'The ID of the comment that was liked, if applicable.',
                'example' => '1',
            ],
        ];
    }
}
