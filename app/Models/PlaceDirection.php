<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlaceDirectionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

final class PlaceDirection extends Model
{
    /** @use HasFactory<PlaceDirectionFactory> */
    use HasFactory;
    use HasUlids;
    use Searchable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address_id',
        'place_id',
        'description',
        'main_text',
        'secondary_text',
    ];

    /**
     * Get the address that owns the PlaceDirection
     *
     * @return BelongsTo<Address, PlaceDirection>
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     *  @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'address_id'])]
    #[SearchUsingFullText(['place_id', 'description', 'main_text', 'secondary_text'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'address_id' => $this->address_id,
            'place_id' => $this->place_id,
            'description' => $this->description,
            'main_text' => $this->main_text,
            'secondary_text' => $this->secondary_text,
        ];
    }
}
