<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\DateTimeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
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
            'liked' => $this->liked,
            'createdAt' => new DateTimeResource(
                resource: $this->created_at,
            ),
            'updatedAt' => $this->updated_at,
            'user' => UserResource::make($this->whenLoaded('user')),
            'post' => PostResource::make($this->whenLoaded('post')),
            'comment' => CommentResource::make($this->whenLoaded('comment')),
            'likesCount' => $this->whenCounted('likes'),
        ];
    }
}
