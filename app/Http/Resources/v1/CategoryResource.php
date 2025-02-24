<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\DateTimeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'createdAt' => new DateTimeResource(
                resource: $this->created_at,
            ),
            'posts' => PostResource::collection($this->whenLoaded('posts')),

            'image' => $this->getFirstMediaUrl(collectionName: 'categories'),
            'thumbnail' => $this->getFirstMediaUrl(
                collectionName: 'categories',
                conversionName: 'thumb',
            ),

            'updatedAt' => $this->updated_at,
            'postsCount' => $this->whenCounted('posts'),
        ];
    }
}
