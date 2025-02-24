<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\DateTimeResource; // ✅ Corrigé
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'createdAt' => new DateTimeResource($this->created_at),
            'user' => UserResource::make($this->whenLoaded('user')),
            'post' => PostResource::make($this->whenLoaded('post')),
            'commentsCount' => $this->whenCounted('comments'),
        ];
    }
}
