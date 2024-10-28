<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'emailVerifiedAt' => $this->email_verified_at,
            'image' => $this->image,
            'phone' => $this->phone,
            'role' => $this->role,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'usersCount' => $this->whenCounted('users'),
        ];
    }
}
