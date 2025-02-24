<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\DateTimeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'slug' => $this->slug,
            'user' => UserResource::make($this->whenLoaded('user')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),

            'tags' => TagResource::collection(
                resource: $this->whenLoaded(
                    relationship: 'tags',
                ),
            ),
            'createdAt' => new DateTimeResource(
                resource: $this->created_at,
            ),

            'image' => $this->getFirstMediaUrl(collectionName: 'posts'),
            'thumbnail' => $this->getFirstMediaUrl(
                collectionName: 'posts',
                conversionName: 'thumb',
            ),
        ];
    }
}
