<?php

declare(strict_types=1);

namespace App\Http\Resources\v1;

use App\Http\Resources\DateTimeResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Address
 */
final class AddressResource extends JsonResource
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
            'placeId' => $this->place_id,
            'placeName' => $this->place_name,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'formatedAddress' => $this->formated_address,
            'streetName' => $this->street_name,
            'user' => new UserResource(
                resource: $this->whenLoaded(
                    relationship: 'user',
                ),
            ),
            'createdAt' => new DateTimeResource(
                resource: $this->created_at,
            ),
        ];
    }
}
