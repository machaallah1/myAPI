<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

final class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;
    use HasUlids;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'place_id',
        'place_name',
        'longitude',
        'latitude',
        'formated_address',
        'user_id',
        'street_name',
    ];

    protected $with = [
        'user',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'user_id'])]
    #[SearchUsingFullText(['place_id', 'formated_address', 'place_name', 'street_name'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'place_id' => $this->place_id,
            'formated_address' => $this->formated_address,
            'place_name' => $this->place_name,
            'street_name' => $this->street_name,
        ];
    }

    /**
     * Get the customer associated with this address
     *
     * @return BelongsTo<User, Address>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }
}
