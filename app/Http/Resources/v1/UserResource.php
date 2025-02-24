<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\DateTimeResource;
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
            'lastName' => $this->last_name,
            'firstName' => $this->first_name,
            'email' => $this->email,
            'emailVerifiedAt' => $this->email_verified_at,
            'phone' => $this->phone,
            'role' => $this->role,
            'image' => $this->getFirstMediaUrl(collectionName: 'users'),
            'thumbnail' => $this->getFirstMediaUrl(
                collectionName: 'users',
                conversionName: 'thumb',
            ),
            'usersCount' => $this->whenCounted('users'),
            'addressesCount' => $this->whenCounted(
                relationship: 'addresses',
            ),
            'addresses' => AddressResource::collection(
                resource: $this->whenLoaded(
                    relationship: 'addresses',
                ),
            ),
            'createdAt' => new DateTimeResource(
                resource: $this->created_at,
            ),
        ];
    }
}
